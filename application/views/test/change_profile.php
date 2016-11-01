<html>
<head>
<title>face book login test</title>
</head>
<body style="width: 1000px; margin-left: auto; margin-right: auto; background-color: #EEEEEE">

<?php echo $error;?>

<?php echo form_open_multipart('http://localhost/Rockz/index.php/mobile/change_profile');?>
    
    <br /><br />
    <br /><br />
  
    <input type="text" name="userid" placeholder="userid" value="15"/><br /><br />
    <input type="text" name="firstname" placeholder="firstname" value=""/><br /><br />
    <input type="text" name="lastname" placeholder="lastname" value=""/><br /><br />
    <input type="text" name="email" placeholder="email" value="jasonisme...gmail.com"/><br /><br />
    <input type="text" name="password" placeholder="password" value=""/><br /><br />
  
    
    <br /><br />
    <input type="file" name="photo" size="20" />
    <br /><br />
    <br /><br />
    <input type="file" name="coverphoto" size="20" />
    <br /><br />

    <input type="submit" value="upload" />

</form>

</body>
</html>