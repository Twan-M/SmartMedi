<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <title>SmartMedi - Voice Control</title>
  <link rel="stylesheet" href="static/style.css">
</head>
<body>

<div class="row">
  <div class="col-md-12" style="margin-left: 10vw; max-width: 80vw; ">
      <h1> SmartMedi Dashboard </h1>

      <!-- Reminders Table -->
      <fieldset>
        <legend><span class="number">1</span> Start voice assistant:</legend>
      <button id="voiceBtn">START</button>
      </fieldset>
      <fieldset>
        <legend><span class="number">2</span> Response:</legend>
      <button class="button-alt"><p id="response"></p></button>
      </fieldset>
</div>
</div>

<script>
// Check of de browser spraakherkenning ondersteunt
if ('webkitSpeechRecognition' in window) {
  const recognition = new webkitSpeechRecognition();
  recognition.lang = 'nl-NL';

  const voiceBtn = document.getElementById('voiceBtn');
  const response = document.getElementById('response');

  // Functie die uitgevoerd wordt wanneer de gebruiker op de knop drukt
  voiceBtn.addEventListener('click', () => {
    recognition.start();
  });

  // Wat er gebeurt als de spraakherkenning een resultaat heeft
  recognition.onresult = async (event) => {
    const transcript = event.results[0][0].transcript;
    console.log('Gevonden tekst:', transcript);

    // Extract the medication name from the spoken text
    const match = transcript.match(/hoeveel pillen (.+?) heb ik nog/i);
    if (match) {
      const medication = match[1].trim();

      // Stuur een fetch request naar de PHP-server om gegevens op te halen
      const responseData = await fetch(`get_pills.php?medication=${encodeURIComponent(medication)}`);
      const data = await responseData.json();

      if (data.success) {
        response.textContent = `Je hebt nog ${data.supply} pillen van ${medication}.`;
      } else {
        response.textContent = `Het medicijn ${medication} is niet gevonden.`;
      }
    } else {
      response.textContent = "Sorry, ik kon geen medicijnnaam herkennen in de vraag.";
    }
  };

  recognition.onerror = (event) => {
    console.log('Error occurred in recognition:', event.error);
    response.textContent = "Er is een fout opgetreden bij de spraakherkenning.";
  };
} else {
  alert("Spraakherkenning wordt niet ondersteund in deze browser.");
}
</script>

</body>
</html>