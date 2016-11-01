<html>
<head>
<title>face book login test</title>
</head>
<body style="width: 1000px; margin-left: auto; margin-right: auto; background-color: #EEEEEE">

<?php echo $error;?>

<?php echo form_open_multipart('http://localhost/Rockz/index.php/mobile/register');?>
    
    <br /><br />
    <br /><br />
  
    <input type="text" name="firstname" placeholder="firstname" value="Jhon"/><br /><br />
    <input type="text" name="lastname" placeholder="lastname" value="Dimmy"/><br /><br />
    <input type="text" name="username" placeholder="username" value="Loveme"/><br /><br />
    <input type="text" name="email" placeholder="email" value="jasonisme...gmail.com"/><br /><br />
    <input type="text" name="password" placeholder="password" value="password"/><br /><br />
    <input type="text" name="devicetoken" placeholder="device token" value="lslkdklsd" /><br /><br />
    <input type="text" name="devicetype" placeholder="0" value="0" /><br /><br />
  
    
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