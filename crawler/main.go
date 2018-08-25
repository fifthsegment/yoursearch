package main

import (
	"fmt"
	"net/http"
	"io/ioutil"
	"github.com/PuerkitoBio/fetchbot"
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
func exists(data string) string {
	url := check_url_endpoint
    // url := "http://35.156.173.29.nip.io/index.php/Api/recordUrl"
    // fmt.Println("URL:>", url)

    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
    // req.Header.Set("X-Custom-Header", "myvalue")
    // req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()

    // fmt.Println("response Status:", resp.Status)
    // fmt.Println("response Headers:", resp.Header)
    body, _ := ioutil.ReadAll(resp.Body)
    // fmt.Println("response Body:", string(body))
    return string(body)
}
func existsincache(data string) string {
	url := check_url_endpoint
    // url := "http://35.156.173.29.nip.io/index.php/Api/recordUrl"
    // fmt.Println("URL:>", url)

    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url+"?inCache=true", bytes.NewBuffer(jsonStr))
    // req.Header.Set("X-Custom-Header", "myvalue")
    // req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()

    // fmt.Println("response Status:", resp.Status)
    // fmt.Println("response Headers:", resp.Header)
    body, _ := ioutil.ReadAll(resp.Body)
    // fmt.Println("response Body:", string(body))
    d:= string(body)
    fmt.Println("Exists in cache = " +d)
    return d;
}
func consume(){
	c := colly.NewCollector()
	fmt.Println("Starting consumer")
	// Find and visit all links
	c.OnHTML("a[href]", func(e *colly.HTMLElement) {
		link := e.Attr("href")
		// ssb:=string(e.Response.Body)

		x := &simplestruct{
			Url: e.Request.AbsoluteURL(e.Request.URL.String()),
			Data: ""}
		rr, _ := json.Marshal(x)
		data := string(rr)
		// rp := exists(data)


		// if (existsincache(data)!="Have"){
		// 	p := strings.NewReader(string(ssb))
		// 	doc, _ := goquery.NewDocumentFromReader(p)

		// 	doc.Find("script").Each(func(i int, el *goquery.Selection) {
		// 	  el.Remove()
		// 	})

		// 	docText := doc.Text()
		// 	// bodyBytes, _ := ioutil.ReadAll(r.Body)
		// 	// bodyString := string(bodyBytes)
		// 	x := &simplestruct{
		// 		// Url: r.Request.URL.String(),
		// 		Url: e.Request.AbsoluteURL(e.Request.URL.String()),
		// 		Data: ssb,
		// 		Content: docText,
		// 	}
		// 	// x.Url = 
		// 	// x.Data = string(body)
		// 	rr, _ := json.Marshal(x)
		// 	data := string(rr)
		// 	// data := `{"url":"`+r.URL.String()+`","data":"``"}`
		// 	recordPage(data) 
		// }

		   	
		
		
		// if (rp != "Have"){
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
		// }else{
		// 	fmt.Println("Visited > " + e.Request.AbsoluteURL(link) )
		// }
		
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
			// x.Url = 
			// x.Data = string(body)
			rr, _ := json.Marshal(x)
			data := string(rr)
    		// data := `{"url":"`+r.URL.String()+`","data":"``"}`
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
			// bodyBytes, _ := ioutil.ReadAll(r.Body)
    		// bodyString := string(bodyBytes)
			x := &simplestruct{
				// Url: r.Request.URL.String(),
				Url: r.Request.AbsoluteURL(start_point),
				Data: string(r.Body),
				Content: docText,
			}
			// x.Url = 
			// x.Data = string(body)
			rr, _ := json.Marshal(x)
			data := string(rr)
    		// data := `{"url":"`+r.URL.String()+`","data":"``"}`
    		recordPage(data)    		
    	}

		
	})
	c.OnError(func(r *colly.Response, err error) {
		fmt.Println("Request URL:", r.Request.URL, "failed with response:", r, "\nError:", err)
		x := &simplestruct{
			// Url: r.Request.URL.String(),
			Url: r.Request.AbsoluteURL(start_point),
			Data: "",
			Content: "",
		}
		// x.Url = 
		// x.Data = string(body)
		rr, _ := json.Marshal(x)
		data := string(rr)
		// data := `{"url":"`+r.URL.String()+`","data":"``"}`
		recordPage(data) 
	})

	c.Visit(start_point)
}

func getStartPoint()string{
	jsonStr := []byte("")
	req, err := http.NewRequest("GET", get_url_endpoint, bytes.NewBuffer(jsonStr) )
    // req.Header.Set("X-Custom-Header", "myvalue")
    // req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()

    // fmt.Println("response Status:", resp.Status)
    // fmt.Println("response Headers:", resp.Header)
    body, _ := ioutil.ReadAll(resp.Body)
    // fmt.Println("response Body:", string(body))
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
	// fmt.Println("U" + record_url_endpoint)
	// recordUrl( "http://ask.com")
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

	// fmt.Println(start_point)
	// os.Exit(1)
	
	os.Exit(1)
	// fmt.Println("DB Host = " + db_host)
	f := fetchbot.New(fetchbot.HandlerFunc(handler))
	queue := f.Start()
	queue.SendStringGet("http://google.com", "http://golang.org", "http://golang.org/doc")
	queue.Close()
}

func recordUrl( data string ) {
	url := record_url_endpoint
    // url := "http://35.156.173.29.nip.io/index.php/Api/recordUrl"
    // fmt.Println("URL:>", url)

    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
    // req.Header.Set("X-Custom-Header", "myvalue")
    // req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
}

func recordPage( data string ) {
	url := savedata_url_endpoint
    // url := "http://35.156.173.29.nip.io/index.php/Api/recordUrl"
    // fmt.Println("URL:>", url)

    var jsonStr = []byte(data)
    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
    // req.Header.Set("X-Custom-Header", "myvalue")
    // req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        panic(err)
    }
    defer resp.Body.Close()
    body, _ := ioutil.ReadAll(resp.Body)
    fmt.Println("response Body:", string(body))
}




func saveBody(url string, body string){
	fmt.Println("Saving body for url = " +url)
}

func handler(ctx *fetchbot.Context, res *http.Response, err error) {

	if err != nil {
		fmt.Printf("error: %s\n", err)
		return
	}
	bodyBytes, _ := ioutil.ReadAll(res.Body)
    bodyString := string(bodyBytes)
	saveBody(ctx.Cmd.URL().String(), bodyString)
	fmt.Printf("[%d] %s %s\n", res.StatusCode, ctx.Cmd.Method(), ctx.Cmd.URL())
}
