<?php

const AREA = 'login';
require __DIR__ . '/lib/init.php';

?>
<meta http-equiv="Cache-Control" content="no-store" />
<style>
 @keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}


 @-webkit-keyframes rotate {
    from {
        -webkit-transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(360deg);
    }
}

.load {
        width: 9em;
        height: 9em;
        margin: 7em auto 0;
        border:solid 1em #2261aa;
        border-radius: 50%;
        border-right-color: transparent;
        border-bottom-color: transparent;
         -webkit-transition: all 0.5s ease-in;
    -webkit-animation-name:             rotate;
    -webkit-animation-duration:         1.0s;
    -webkit-animation-iteration-count:  infinite;
    -webkit-animation-timing-function: linear;

     transition: all 0.5s ease-in;
    animation-name:             rotate;
    animation-duration:         1.0s;
    animation-iteration-count:  infinite;
    animation-timing-function: linear;
}

.flexbox-center{
  display: flex;
  justify-content: center;
  margin-top: 2em;
}

.button{
  background: #2261aa;
  line-height: 2.5em;
  text-align: center;
  color: #fff;
  font-size: 2em;
  padding-right: 0.5em;
  padding-left: 0.5em;
}
</style>

<form id="redirectLogin" action="index.php" method="post">

<?php
    foreach ($_POST as $name => $value) {
        echo '<input type="hidden" name="'.htmlentities($name).'" value="'.htmlentities($value).'">';
    }
?>

  <noscript>
    <div class="load"></div>
    <div class="flexbox-center">
      <input class="button" type="submit" value="Click to login"/>
    </div>
  </noscript>
</form>

<script type="text/javascript">
    document.getElementById('redirectLogin').submit();
</script>