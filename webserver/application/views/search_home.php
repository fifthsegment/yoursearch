<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>YourSearch an Opensource search engine</title>
        <meta name="description" content="A simple free open source indexer + search engine">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSS Dependencies -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="/assets/shards.min.css?version=2.0.1">
        <link rel="stylesheet" href="/assets/shards-extras.min.css?version=2.0.1">
    </head>
    <body class="shards-landing-page--1">
      <!-- Welcome Section -->
      <div class="welcome d-flex justify-content-center flex-column">
        <div class="container">
          <!-- Navigation -->
          <nav class="navbar navbar-expand-lg navbar-dark pt-4 px-0">
            <a class="navbar-brand" href="#">
              
              YourSearch
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
              <ul class="navbar-nav">
                
                <li class="nav-item">
                  <a class="nav-link" href="https://github.com/fifthsegment/yoursearch">Github</a>
                </li>
              </ul>

              
            </div>
          </nav>
          <!-- / Navigation -->
        </div> <!-- .container -->

        <!-- Inner Wrapper -->
        <div class="inner-wrapper mt-auto mb-auto container ">
          <div class="row justify-content-md-center px-4">
            <div class="contact-form col-sm-12 col-md-10 col-lg-7 p-4 mb-4 card">
                    <h1 class="welcome-heading display-3 text-center" style="color:#40a6ff; margin-bottom:30px">YourSearch</h1>
              <form action="/Search/results" method="GET">
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      
                      <input type="text" class="form-control" name="search" placeholder="Search for ...">
                    </div>
                    <div style="font-size:10px !important; ">
                    Pages indexed + cached : <?php echo $res ?>
                    </div>
                  </div>
                  
                </div>
                <div class="row">
                  
                </div>
                <input class="btn btn-primary btn-pill d-flex ml-auto mr-auto" type="submit" value="Search">
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4  ">
                
                
                
            </div>
          </div>
        </div>
        <!-- / Inner Wrapper -->
      </div>
      <!-- / Welcome Section -->




      

      

      <!-- Footer Section -->
      <footer>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
          <div class="container">
            <a class="navbar-brand" href="#">YourSearch</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              
            </div>

          </div>
        </nav>
      </footer>
      <!-- / Footer Section -->

      <!-- JavaScript Dependencies -->
      <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
