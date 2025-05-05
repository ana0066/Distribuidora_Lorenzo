<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="../css/comentarios.css">
  <title>Form Reviews</title>
</head>
<body>

  <div class="wrapper">
    <h3>Comparte tu experiencia</h3>
    <form id="review-form">
      <input type="text" name="username" placeholder="Tu nombre..." required>
      <div class="rating">
        <input type="number" name="rating" hidden>
        <i class='bx bx-star star' style="--i: 0;"></i>
        <i class='bx bx-star star' style="--i: 1;"></i>
        <i class='bx bx-star star' style="--i: 2;"></i>
        <i class='bx bx-star star' style="--i: 3;"></i>
        <i class='bx bx-star star' style="--i: 4;"></i>
      </div>
      <textarea name="opinion" cols="30" rows="5" placeholder="Tu comentario..." required></textarea>
      <div class="btn-group">
        <button type="submit" class="btn submit">Enviar</button>
        <button type="button" class="btn cancel" onclick="clearForm()">Cancelar</button>
      </div>
    </form>
  </div>

  <div class="comments-section">
    <h3>Comentarios recientes</h3>
    <div id="comments-container"></div>
  </div>

  <script src="../js/comentario.js" async></script> 
</body>
</html>
