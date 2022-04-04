<!DOCTYPE html>
<html>

<body>

  <form action="ecxelupload" method="post" enctype="multipart/form-data">
    @csrf
    Select ecxel to upload:
    <input type="file" name="newfile" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
  </form>

</body>

</html>