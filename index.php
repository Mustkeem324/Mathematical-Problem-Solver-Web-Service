<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url=$_GET['url']; //request get to url
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $text = $url;
    }
    else{
        echo "Invalid URL provided check url";
        exit();
    }
    //get the content device_id
    function device_id() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.szl.ai/users/register_device',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"signed_token":"eyJ0aW1lc3RhbXAiOjE3MTExODU5NzguOTU2fQ==.0c98cdef596221a88285f53e7aec3c272284b80e6053f8b17bd565e2b3e21aaf"}',
            CURLOPT_HTTPHEADER => array(
                    'accept: */*',
                    'accept-language: en-US,en;q=0.9',
                    'content-type: application/json',
                    //'cookie: _fbp=fb.1.1711185978377.1547826231; _ga=GA1.1.576165090.1711185979; _gcl_au=1.1.43679434.1711185979; _ga_D3T6T1E0YW=GS1.1.1711185978.1.0.1711185978.0.0.0',
                    'dnt: 1',
                    'origin: https://web.szl.ai',
                    'referer: https://web.szl.ai/',
                    'rid: anti-csrf',
                    'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
                    'sec-ch-ua-mobile: ?0',
                    'sec-ch-ua-platform: "Linux"',
                    'sec-fetch-dest: empty',
                    'sec-fetch-mode: cors',
                    'sec-fetch-site: same-site',
                    'st-auth-mode: header',
                    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
                    'x-application-name: sizzle-web',
                    'x-sent-at-timestamp: 1711185978.961'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json_response = json_decode($response, true);

        if ($json_response && isset($json_response['device_id'])) {
            //echo $json_response['device_id'];
            return $json_response['device_id'];
        } else {
            echo "Error decoding JSON\n";
            return null;
        }
    }

    //get ocrmathtext
    function ocrmathtext($file_photo, $device_id_value) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.szl.ai/steps/ocr_image?device_id='.$device_id_value,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($file_photo)),
                CURLOPT_HTTPHEADER => array(
                        'accept: */*',
                        'accept-language: en-US,en;q=0.9',
                        //'cookie: _ga=GA1.1.668285594.1711185908; _gcl_au=1.1.290764441.1711185908; _fbp=fb.1.1711185909522.1662266317; _ga_D3T6T1E0YW=GS1.1.1711185908.1.1.1711186390.0.0.0',
                        'dnt: 1',
                        'origin: https://web.szl.ai',
                        'referer: https://web.szl.ai/',
                        'rid: anti-csrf',
                        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
                        'sec-ch-ua-mobile: ?0',
                        'sec-ch-ua-platform: "Linux"',
                        'sec-fetch-dest: empty',
                        'sec-fetch-mode: cors',
                        'sec-fetch-site: same-site',
                        'st-auth-mode: header',
                        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
                        'x-application-name: sizzle-web',
                        'x-sent-at-timestamp: 1711186427.716'
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

        if ($response === false) {
            echo "Error: " . curl_error($ch);
        } else {
            $data = json_decode($response, true);
            if (isset($data['error'])) {
                echo "Error found in response: " . $data['ocr_text'] . "\n";
            } else {
                //echo $data['ocr_text'] . "\n";
                return $data['ocr_text'];
            }
        }
    
    }

    //get upload problem text
    function upload_problem_text($ocr_text, $device_id_value) {
        $problem_text = $ocr_text;
        $data = array(
            'problem_text' => $problem_text
        );
        $json_data = json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.szl.ai/steps/upload_problem_text?device_id='.$device_id_value,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>''.$json_data.'',
                CURLOPT_HTTPHEADER => array(
                        'accept: */*',
                        'accept-language: en-US,en;q=0.9',
                        'content-type: application/json',
                        //'cookie: _ga=GA1.1.668285594.1711185908; _gcl_au=1.1.290764441.1711185908; _fbp=fb.1.1711185909522.1662266317; _ga_D3T6T1E0YW=GS1.1.1711185908.1.1.1711186390.0.0.0',
                        'dnt: 1',
                        'origin: https://web.szl.ai',
                        'referer: https://web.szl.ai/',
                        'rid: anti-csrf',
                        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
                        'sec-ch-ua-mobile: ?0',
                        'sec-ch-ua-platform: "Linux"',
                        'sec-fetch-dest: empty',
                        'sec-fetch-mode: cors',
                        'sec-fetch-site: same-site',
                        'st-auth-mode: header',
                        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
                ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;

        $json_response = json_decode($response, true);
        
        if ($json_response && isset($json_response['work_session_id'])) {
            //echo  "Work session ID: ". $json_response['work_session_id'] . "\n";
            return $json_response['work_session_id'];
        } elseif ($json_response && isset($json_response['error'])) {
            echo "Error found in response: " . $json_response['work_session_id'] . "\n";
            return null;
        } else {
            echo "An unexpected error occurred\n";
            return null;
        }

    }

    //get genrate step
    function generate_steps($work_session_id_value, $device_id_value) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.szl.ai/steps/generate_steps?work_session_id='.$work_session_id_value.'&device_id='.$device_id_value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                    'accept: */*',
                    'accept-language: en-US,en;q=0.9',
                    //'cookie: _ga=GA1.1.668285594.1711185908; _gcl_au=1.1.290764441.1711185908; _fbp=fb.1.1711185909522.1662266317; _ga_D3T6T1E0YW=GS1.1.1711185908.1.1.1711186390.0.0.0',
                    'dnt: 1',
                    'origin: https://web.szl.ai',
                    'referer: https://web.szl.ai/',
                    'rid: anti-csrf',
                    'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
                    'sec-ch-ua-mobile: ?0',
                    'sec-ch-ua-platform: "Linux"',
                    'sec-fetch-dest: empty',
                    'sec-fetch-mode: cors',
                    'sec-fetch-site: same-site',
                    'st-auth-mode: header',
                    'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        //echo $response;
        $json_response = json_decode($response, true);
        
        if ($json_response && isset($json_response['work_session_id'])) {
            //echo $json_response['work_session_id'] . "\n";
            return $json_response['work_session_id'];
        } else {
            echo "An unexpected error occurred\n";
            return null;
        }

    }

    //get revel answer of every steps
    function reveal_answers($work_session_id_value, $device_id_value) {
        $max_step_number = 20;
        $all_answers = array();
        $answer_found = false;

        for ($step_number = 0; $step_number <= $max_step_number; $step_number++) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.szl.ai/steps/reveal_answer?work_session_id='.$work_session_id_value.'&step_number='.$step_number.'&device_id='.$device_id_value,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                        'accept: */*',
                        'accept-language: en-US,en;q=0.9',
                        //'cookie: _ga=GA1.1.668285594.1711185908; _gcl_au=1.1.290764441.1711185908; _fbp=fb.1.1711185909522.1662266317; _ga_D3T6T1E0YW=GS1.1.1711189841.2.1.1711189859.0.0.0',
                        'dnt: 1',
                        'origin: https://web.szl.ai',
                        'referer: https://web.szl.ai/',
                        'rid: anti-csrf',
                        'sec-ch-ua: "Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
                        'sec-ch-ua-mobile: ?0',
                        'sec-ch-ua-platform: "Linux"',
                        'sec-fetch-dest: empty',
                        'sec-fetch-mode: cors',
                        'sec-fetch-site: same-site',
                        'st-auth-mode: header',
                        'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;
            $response_json = json_decode($response, true);
        
            if (isset($response_json['detail']) && $response_json['detail'] === "Step number $step_number not found") {
                break;
            } elseif (isset($response_json['answer'])) {
                $answertext = $response_json['answer']['answer'];
                $all_answers[] = $answertext;
                $answer_found = true;
            } else {
                break; // No more steps
            }
        }
        
        if ($answer_found) {
            $combined_answers = implode('<br>', $all_answers);
            $answer = '<p>' . $combined_answers . '</p>';
            //echo $answer;
            return $answer;
        } else {
            echo 'No answers found.';
            return false;
        }
    }

    $device_id_value = device_id();
    $file_photo = $text;
    $ocr_text = ocrmathtext($file_photo, $device_id_value);
    $work_session_id_value = upload_problem_text($ocr_text, $device_id_value);
    $work_session_id_value2 = generate_steps($work_session_id_value, $device_id_value);
    $reveal_answers = reveal_answers($work_session_id_value2, $device_id_value);
    $answerhtml ='<!DOCTYPE html><html><head> <meta charset="utf-8"/> <meta name="viewport" content="width=device-width, initial-scale=1"/> <title>NX pro</title> <meta name="description" content=""/> <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"/> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"/> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script></head><body> <div class="container" id="app"> <div class="box"> <div class="content"><div class="answer"><h4>Question</h4>'.$ocr_text.'<br><br><!-- Your answer content goes here --><h4>Answer</h4>'.$reveal_answers.'</div> </div> </div> </div> </div> <script type="text/x-mathjax-config"> MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX", "input/MathML", "output/HTML-CSS", "output/NativeMML"], extensions: ["tex2jax.js", "mml2jax.js", "MathMenu.js", "MathZoom.js"], TeX: { extensions: ["AMSmath.js", "AMSsymbols.js", "noErrors.js", "noUndefined.js"] } }); </script> <script type="text/javascript" src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script> <script id="MathJax-script" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.js"></script></body></html>';
    if(isset($reveal_answers)){
        echo $answerhtml;
    }else{
        $answerhtml ='<!DOCTYPE html><html><head> <meta charset="utf-8"/> <meta name="viewport" content="width=device-width, initial-scale=1"/> <title>NX pro</title> <meta name="description" content=""/> <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon"/> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css"/> <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.min.js"></script></head><body> <div class="container" id="app"> <div class="box"> <div class="content"><div class="answer"><h1>Answer Not found</h1><br><h2>403 Forbidden</h2><!-- Your answer content goes here --></div> </div> </div> </div> </div> <script type="text/x-mathjax-config"> MathJax.Hub.Config({ config: ["MMLorHTML.js"], jax: ["input/TeX", "input/MathML", "output/HTML-CSS", "output/NativeMML"], extensions: ["tex2jax.js", "mml2jax.js", "MathMenu.js", "MathZoom.js"], TeX: { extensions: ["AMSmath.js", "AMSsymbols.js", "noErrors.js", "noUndefined.js"] } }); </script> <script type="text/javascript" src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script> <script id="MathJax-script" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/3.2.0/es5/tex-mml-chtml.js"></script></body></html>';
        echo $answerhtml;
    }
}else{
    http_response_code(405);  //method not allowed
    die("Method Not Allowed");
}
