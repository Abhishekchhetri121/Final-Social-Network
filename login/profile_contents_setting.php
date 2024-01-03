<div id="d2" style="width: 97.5%;  text-align:center; background-color:white">
    <div style="padding :20px;  display: inline-block;"></div>

    <form action="" method="post" enctype="multipart/form-data">

        <?php

        $setting_class = new Settings();
        $settings = $setting_class->get_settings($_SESSION['SOCIAL_userid']);
        if (is_array($settings)) {

            echo "<br><input type='text' id ='textboxes' value='" . htmlspecialchars($settings['first_name']) . "' name='first_name' placeholder='First Name'><br>";
            echo "<input type='text' id ='textboxes'value='" . htmlspecialchars($settings['last_name']) . "'  name='last_name' placeholder='Last Name'><br>";

            echo "<input type='text' id ='textboxes' value='" . htmlspecialchars($settings['email']) . "'  name='email' placeholder='Email'><br>";
            echo "<input type='text' id ='textboxes' value='" . htmlspecialchars($settings['semester']) . "'  name='semester' placeholder='Semester'><br>";
            echo "<input type='password' id ='textboxes' value='" . htmlspecialchars($settings['password']) . "'  name='password' placeholder='New Password'><br>";
            echo "<input type='password' id ='textboxes' value='" . htmlspecialchars($settings['password']) . "'  name='password2' placeholder=' Reenter New Password'><br>";

                /*echo "<br>About me:<br>
                    <textarea id='textboxes' style='height:200px' name='about'>'" . htmlspecialchars($settings['about']) . "'</textarea><br>"*/;
            echo '<input type="submit" id="post_btn" value="Save"><br>';
        }

        ?>
    </form>
</div>