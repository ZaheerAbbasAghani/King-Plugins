<?php
// create custom plugin settings menu
add_action('admin_menu', 'qbu_plugin_create_menu');

function qbu_plugin_create_menu()
{

    //create new top-level menu
    add_menu_page('Q Bulk Upload Settings', 'Q Bulk Uploads', 'manage_options', "q_bulk_uploads", 'qbu_plugin_settings_page', "dashicons-upload", 30);

    //call register settings function
    //add_action( 'admin_init', 'register_qbu_plugin_settings' );
}


function register_qbu_plugin_settings()
{
    //register our settings
    /*	register_setting( 'my-cool-plugin-settings-group', 'new_option_name' );
        register_setting( 'my-cool-plugin-settings-group', 'some_other_option' );
        register_setting( 'my-cool-plugin-settings-group', 'option_etc' );*/
}

function qbu_plugin_settings_page()
{
    ?>
    <div class="wrap" style="background: #fff; padding: 10px 20px">
        <h1>Q Bulk Upload Settings</h1>
        <hr>

        <form method="post" action="" enctype="multipart/form-data">
            <?php settings_fields('my-cool-plugin-settings-group'); ?>
            <?php do_settings_sections('my-cool-plugin-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Upload DOCX File</th>
                    <td><input type="file" name="upload_docx_file"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Reverse questions</th>
                    <td><input type="checkbox" name="reverse" value="1"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Remove question number?</th>
                    <td><input type="checkbox" name="delete_quest_num" value="1"/></td>
                </tr>
            </table>

            <?php submit_button(); ?>

        </form>


        <?php

        function clear_load_text($text, $clear_pre = false) {
            $reg = "/^(?:[\d]*?|[\w]{1})\.[\s]?/";
            $text = str_replace(' 	', '', $text);
            $text = trim($text);

            return $clear_pre ? preg_replace($reg, '', $text, 1) : $text;
        }
        if (($_FILES['upload_docx_file']['name'] != "")) {
// Where the file is going to be stored
            $target_dir = wp_upload_dir();
            $file = $_FILES['upload_docx_file']['name'];
            $path = pathinfo($file);
            $num = rand(0, 1000);
            //$filename = trim($path['filename']," ").'_'.$num;
            //str_replace(' ', '', $str)
            $filename = str_replace(' ', '', $path['filename']) . '_' . $num;
            $ext = $path['extension'];
            $temp_name = $_FILES['upload_docx_file']['tmp_name'];
            $path_filename_ext = $target_dir['path'] . '/' . $filename . "." . $ext;

            // Check if file already exists
            if (file_exists($path_filename_ext)) {
                echo "Sorry, file already exists.";
            } else {
                move_uploaded_file($temp_name, $path_filename_ext);

                $isLoadReverse = isset($_POST['reverse']);
                $isRemoveQuestNum = isset($_POST['delete_quest_num']);

				$newRecordCount = $updateRecordCount = 0;

                try {
                    require 'vendor/autoload.php';
                    require 'simple_html_dom.php';

                    $phpWord = \PhpOffice\PhpWord\IOFactory::load($path_filename_ext);
                    // Adding an empty Section to the document...
//                     $section = $phpWord->addSection();
                    // Adding Text element to the Section having font styled by default...
//                     $section->addText($data);
//                     $section->addTextBreak(6);
                    //$textrun = $section->addTextRun();

                    //$name= rand(0,1000);
                    $source = __DIR__ . "/results/{$filename}.html";

                    // Saving the document as HTML file...
                    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
                    $objWriter->save($source);

                    //$fsrc = __DIR__.'/results/'.$filename.'.html';
                    $fsrc = plugin_dir_url(__FILE__) . 'results/' . $filename . '.html';

                    //echo $fsrc.'<br>';

                    $html = file_get_html($fsrc);

                    //$i=1;
                    $array = array();
                    foreach ($html->find('p') as $element) {
                        array_push($array, wordwrap($element->plaintext));
                    }

                    $newArray = array();
                    $subArray = array();
                    for ($i = 0; $i < count($array); $i++) {

                        $v = trim($array[$i]);
                        if (!empty($v) && $v !== '&nbsp;') {
                            array_push($subArray, $v);
                        } else {
                            if (count($subArray) > 0) array_push($newArray, $subArray);
                            $subArray = array();
                        }
                    }

                    if ($subArray) array_push($newArray, $subArray);

                    if ($isLoadReverse) {
                        $newArray = array_reverse($newArray);
                    }

                    $sort = 0;
                    $questionMapper = new WpProQuiz_Model_QuestionMapper();

                    $questionCount = count($newArray);
                    $now        = new DateTime( 'now', wp_timezone() );
                    $now_gmt    = new DateTime( 'now', new DateTimeZone( 'UTC' ) );
                    $interval   = new DateInterval('PT'.$questionCount.'S');
                    $now->sub($interval);
                    $now_gmt->sub($interval);
                    $interval   = new DateInterval('PT1S');

                    foreach ($newArray as $new) {

                        $now->add($interval);
                        $now_gmt->add($interval);

                        $post_title = clear_load_text(array_shift($new), $isRemoveQuestNum);

                        if (empty($post_title)) continue;

                        $post_title_quest = $post_title;
                        $reg = "/^\d*?\.[\s]?/";
                        $isCompose = false;
                        $ar = array();
                        if (preg_match($reg, $new[1]) === 1 ) {
                            $isCompose = true;
                            $post_title_quest .= '<br />' . implode('<br />', $new);
                            $ar = ['A', 'B', 'C', 'D', 'E'];
                        } else {
                            $ar = array_map(function($i) {
                                return clear_load_text($i);
                            },
                                $new
                            );
                        }

                        $post_id = post_exists($post_title);
                        if ($post_id) {
                            $question_pro_id = (int)get_post_meta($post_id, 'question_pro_id', true);
                            $model = $questionMapper->fetch($question_pro_id);
                        } else {
                            $model = new WpProQuiz_Model_Question();
                            $model->setTitle(trim($post_title));
                            $model->setQuizId(0);
                            $model->setId(0);
                            $model->setSort($sort++);
                            $model->setCategoryId(0);
                        }
						$model->setQuestion(trim($post_title_quest));

                        $answers = $ar;
                        $answerData = array();
                        foreach ($answers as $answer) {
                            $answerModel = new WpProQuiz_Model_AnswerTypes();
                            $answerModel->setAnswer($answer);
                            $answerData[] = $answerModel;
                        }
                        $model->setAnswerData($answerData);

                        $question = $questionMapper->save($model);

                        $user_id = get_current_user_id();
                        $question_post_array = array(
                            'post_type' => learndash_get_post_type_slug('question'),
                            'post_title' => $question->getTitle(),
                            'post_content' => $question->getQuestion(),
                            'post_status' => 'publish',
                            'post_date' => $now->format( 'Y-m-d H:i:s' ),
                            'post_date_gmt' => $now_gmt->format( 'Y-m-d H:i:s' ),
                            'post_author' => $user_id,
                            'menu_order' => 0,
                        );
                        $question_post_array = wp_slash($question_post_array);
                        $question_post_id = $post_id 
                            ? wp_update_post($question_post_array)
                            : wp_insert_post($question_post_array);
                        if (!empty($question_post_id)) {
                            update_post_meta($question_post_id, 'points', absint($question->getPoints()));
                            update_post_meta($question_post_id, 'question_type', $question->getAnswerType());
                            update_post_meta($question_post_id, 'question_pro_id', absint($question->getId()));
                            learndash_update_setting($question_post_id, 'quiz', 0);
                            add_post_meta($question_post_id, 'ld_quiz_id', 0);
                        }
						
						if ($post_id) {
							$updateRecordCount++;
						} else {
							$newRecordCount++;
						}
						

                    } // newArray Foreach


                    echo "Generated successfully <br />";
					echo "Added {$newRecordCount} new questions <br />";
					echo "Updated {$updateRecordCount} questions";

                }
                catch
                (Exception $e)
                {
                    echo $e->getLine();
                    echo "<br>/";
                    echo $e->getMessage();
                    echo "<br/>";
                    echo $e->getFile();
                    exit;
                }

            }
        }

        ?>


    </div>
<?php } ?>
