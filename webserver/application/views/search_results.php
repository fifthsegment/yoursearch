<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>YourSearch - Results</title>
        <meta name="description" content="A simple free open source indexer + search engine">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSS Dependencies -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="/assets/shards.min.css?version=2.0.1">
        <link rel="stylesheet" href="/assets/shards-extras.min.css?version=2.0.1">
    </head>
    <body class="shards-landing-page--1">
<!-- Our Services Section -->
      <div id="our-services" class="our-services section py-4">
        <h3 class="section-title text-center my-5"> <?php echo $res ?> Results</h3>
        <!-- Features -->
        <div class="features py-4 mb-4">
          <div class="container">
            <div class="row">
              <?php foreach ($results as $row) { ?>
              <div class="feature py-4 col-md-12 d-flex">
                <div class="icon text-primary mr-3"><i class="fa fa-search"></i></div>
                <div class="px-4">
                    <h5><a href="<?php echo $row['url'] ?>"><?php echo $row['host'] ?></a></h5>
                    <p><?php echo $row['content'] ?></p>
                    <p><u style="font-size:10px;">Found <?php echo $row['occurences'] ?> occurence(s)</u></p>
                    <p>
                      <a style="color:green" href="<?php echo $row['url']?>"><?php echo $row['url'] ?></a>
                    </p>
                </div>
              </div>
              <?php } ?>
              

            
          </div>
        </div>
        <!-- / Features -->
      </div>
      <!-- / Our Services Section -->




      

      

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
