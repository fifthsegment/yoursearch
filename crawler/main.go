package main

import (
	"fmt"
	"net/http"
	"io/ioutil"
	"github.com/evalphobia/go-config-loader"
	"bytes"
	"os"
	"github.com/gocolly/colly"
	"encoding/json"
	"strings"
	"os/signal"
	"github.com/PuerkitoBio/goquery"

)
var record_url_endpoint string 
var check_url_endpoint string 
var get_url_endpoint string 
var savedata_url_endpoint string 
var start_point string
var file_start_point string


type simplestruct struct {
	Url string
	Data string
	Content string
}

/*
* Checks if the url exists in the web index
*/
func exists(data string) string {
	url := check_url_endpoint
    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
    body, _ := ioutil.ReadAll(resp.Body)
    return string(body)
}

/*
* Checks if the url exists in the web cache
*/
func existsincache(data string) string {
	url := check_url_endpoint
    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url+"?inCache=true", bytes.NewBuffer(jsonStr))
    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
    body, _ := ioutil.ReadAll(resp.Body)
    d:= string(body)
    fmt.Println("Exists in cache = " +d)
    return d;
}

/*
* Consumes web page, finds all links and adds them to the web index for crawling later
*/
func consume(){
	c := colly.NewCollector()
	fmt.Println("Starting consumer")
	// Find and visit all links
	c.OnHTML("a[href]", func(e *colly.HTMLElement) {
		link := e.Attr("href")
		x := &simplestruct{
			Url: e.Request.AbsoluteURL(e.Request.URL.String()),
			Data: ""}
		rr, _ := json.Marshal(x)
		data := string(rr)
		
		fmt.Println("HREF > " + e.Request.AbsoluteURL(link))
		/**
		* Add it to the online url db
		*/
		x = &simplestruct{
			Url: e.Request.AbsoluteURL(link),
			Data: ""}
		rr, _ = json.Marshal(x)
		data = string(rr)
		recordUrl(data)    		

		
	})

	c.OnRequest(func(r *colly.Request) {
		
		fmt.Println("Visiting", r.URL)
	})

	c.OnResponse(func(r *colly.Response) {
		record := false
		headerc := strings.ToLower(r.Headers.Get("Content-Type"))
		fmt.Println("Response Headers = " + headerc)
		if strings.Index(headerc, "html") >= 0 {
			record = true;
    	}else{
    		fmt.Println("Response is not HTML")
    		x := &simplestruct{
				Url: r.Request.AbsoluteURL(start_point),
				Data: "",
				Content: "",
				// Header
			}
			rr, _ := json.Marshal(x)
			data := string(rr)
    		recordPage(data) 
    	}


    	if record == true {
    		fmt.Println("Response is HTML, logging it")
    		p := strings.NewReader(string(r.Body))
			doc, _ := goquery.NewDocumentFromReader(p)

			doc.Find("script").Each(func(i int, el *goquery.Selection) {
			  el.Remove()
			})

			docText := doc.Text()
			x := &simplestruct{
				Url: r.Request.AbsoluteURL(start_point),
				Data: string(r.Body),
				Content: docText,
			}
			rr, _ := json.Marshal(x)
			data := string(rr)
    		recordPage(data)    		
    	}
	
	})
	c.OnError(func(r *colly.Response, err error) {
		fmt.Println("Request URL:", r.Request.URL, "failed with response:", r, "\nError:", err)
		x := &simplestruct{
			Url: r.Request.AbsoluteURL(start_point),
			Data: "",
			Content: "",
		}
		rr, _ := json.Marshal(x)
		data := string(rr)
		recordPage(data) 
	})

	c.Visit(start_point)
}


/**
* URL to start crawling from, this is obtained from the server
* if its not found on the server, it loads the url from the file
*/
func getStartPoint()string{
	jsonStr := []byte("")
	req, err := http.NewRequest("GET", get_url_endpoint, bytes.NewBuffer(jsonStr) )
 
    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
    body, _ := ioutil.ReadAll(resp.Body)
    return string(body)
}

func main() {
	conf := config.NewConfig()
	// Load json config file
	argsWithoutProg := os.Args[1:]
	if (len(argsWithoutProg) == 0){
		fmt.Println("Path to Config.json Directory path must be provided as first argument")
		os.Exit(1)
	}
	basePath :=   os.Args[1]
	fmt.Println("Config path = "+ basePath)
	e := conf.LoadConfigs(basePath , "json")
	if (e!= nil){
		fmt.Println(e)
		os.Exit(1)
	}
	fmt.Println(conf.GetConfigValues())
	record_url_endpoint = conf.ValueString("rurlep")
	check_url_endpoint = conf.ValueString("curlep")
	file_start_point = conf.ValueString("start_point")
	get_url_endpoint = conf.ValueString("gurlep")
	savedata_url_endpoint = conf.ValueString("durlep")

	c := make(chan os.Signal, 1)
	signal.Notify(c, os.Interrupt)
	/**
	* Ctrl c catcher
	*/
	go func(){
	    for sig := range c {
	    	_=sig
	        os.Exit(1)

	    }
	}()
	for {
		start_point = getStartPoint()
		if (start_point == "zero"){
			fmt.Println("Using start point from file")
			start_point = file_start_point
		}	
		consume()	

	}

}

/*
* Save URL in the web index
*/
func recordUrl( data string ) {
	url := record_url_endpoint
    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
}

/*
* Save Page contents in the web cache
*/
func recordPage( data string ) {
	url := savedata_url_endpoint
    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
    body, _ := ioutil.ReadAll(resp.Body)
    fmt.Println("response Body:", string(body))
}




