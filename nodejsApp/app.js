const express = require('express');
const mustacheExpress = require('mustache-express');
const axios = require('axios');
const app = express();

app.engine('mustache', mustacheExpress());
app.set('view engine', 'mustache');
app.set('views', __dirname + '/views');

app.get('/', async (req, res) => {
  try {
    const response = await axios.get('http://localhost:8000/api/readComment');
    const jsonData = response.data["result"];

    // Render the 'index.mustache' template and pass the JSON data as a parameter
    //console.log(jsonData);
    res.render('index', { data: jsonData });
  } catch (error) {
    console.error('Error fetching data:', error);
    res.status(500).send('Error fetching data');
  }
});


app.listen(3000, () => {
  console.log('Server started on port 3000');
});