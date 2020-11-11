<?= $this->user->getEmail(); ?>
<br>
<?= $this->user->getFirstName(); ?>
<?= $this->user->getLastName(); ?>


<?php 
// var_Dump($this->data); exit;
?>


<br><br><br><br>

<style>
* {
    font-family: arial;
}
.tag {
    background-color: lightblue;
    color:white;
    border-radius:20px;
    padding:4px;
    font-size: 11px;
}
</style>

<h3><?= $this->user->getFirstName() ?>'s Playlist's:</h3>

<br>

<table>

<?php foreach ($this->data as $playlist) { ?>
    <tr>
        <label style="font-size:22px;"><?= $playlist['name'] ?></label>
        <?php
            if (count($playlist['tags'])) {
                foreach ($playlist['tags'] as $tag) {
                    echo '<span class="tag">'.$tag.'</span>';
                }
            }
        ?>

        <?php 
            if (count($playlist['subPlaylists'])) {
                foreach ($playlist['subPlaylists'] as $sub) {
                    echo '<li>'.$sub['name'].'</li>';

                    if (count($sub['tags'])) {
                        foreach ($sub['tags'] as $tag) {
                            echo '<span class="tag">'.$tag.'</span>';
                        }
                    }


                }
            } else {
                echo '<br>';
            }
        ?>
    </tr>
<?php } ?>
</table>
