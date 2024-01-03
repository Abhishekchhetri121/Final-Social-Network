<div id="mid-panel">
    <!---mates-->
    <div id="d1">
        <div id="fbar">
            Following<br>

            <?php

            $user_class = new User();

            if ($friends) {
                foreach ($friends as $friend) {
                    $row = $user_class->get_user($friend['userid']);
                    include('user.php');
                    echo "<br>";
                }
            }
            ?>

        </div>

    </div>

    <!---posts area--->
    <div id="d2">
        <div id="id2">
            <form action="" method="post" enctype="multipart/form-data">
                <textarea name="post" placeholder="What's on Your mind?"></textarea>
                <input type="file" name="file">
                <input type="submit" id="post_btn" value="post"><br><br>
            </form>
        </div>
        <!----posts --->
        <div id="post_bar">
            <!----posts 1 --->
            <!----posts - 1 ending -->
            <!----posts 2 --->
            <!----posts - 2 ending -->

            <?php
            if ($result) {
                foreach ($result as $row) {

                    $user = new User();
                    $row_user = $user->get_user($row['postid']);
                    include('post.php');
                }
            }

            //get current url
            $pg = paination_link();
            ?>
            <a href="<?= $pg['next_page'] ?>">
                <input type="button" id="post_btn" value="Next Page" style="position:relative; top:40px; cursor:pointer;"><br><br>
            </a>
            <a href="<?= $pg['prev_page'] ?>">
                <input type="button" id="post_btn" value="Prev Page" style="float:left;"><br><br>
            </a>
        </div>
    </div>
</div>