<?php 
//note: create a text file in the folder with this file called "api_key".
//go to https://imagga.com/profile/dashboard copy the api key into your
//text file add a colon and then copy the api secret into your text file.
//your file will look something like: "asd_23489abc:20ac7437bd88239"


if (!isset($_POST["image_path"]) | $_POST["image_path"] == "") { //check to see if the url is set. if not set prompts for a url.
    ?>
    <form action="index.php" method="post">Find tags for image: <input type="text" name="image_path"> <input type="submit"></form>
    <?php
      exit;
    }
    else {
        $image_path = $_POST["image_path"];
        
        $threshold = 50; // set the confidence threshold of tags returned

        //create the header to pass the api key
        $context = stream_context_create(array(
            'http' => array(
                'header'  => "Authorization: Basic " . base64_encode(trim(file_get_contents('api_key')))
            )
        ));

        //grab the json file containing our tags
        $data = file_get_contents("https://api.imagga.com/v2/tags?image_url=$image_path&threshold=$threshold&use_feedback=1", false, $context);

        //show the image
        echo "<img style=\"width:300px;\" src=\"$image_path\"><br><br>";

        //converts the json file to an array
        $tag_array = json_decode ($data,true);

        //loop through each of the tags and display the tags in order of most to least accurate.
        foreach ($tag_array['result']['tags'] as $tag){
            echo $tag['tag']['en'].", ";
        }
    }
?>