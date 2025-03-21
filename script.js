const apiKey = "f3fb9697346f7c5c17a952fd7a92c8be";

document.getElementById("search-btn").addEventListener("click", () => {
  const city = document.getElementById("city-input").value;
  if (city) {
    fetchWeather(city);
    fetchForecast(city);
  }
});

function fetchWeather(city) {
  fetch(
    `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`
  )
    .then((response) => response.json())
    .then((data) => {
      document.getElementById("weather-info").innerHTML = `
                <h3>${data.name}, ${data.sys.country}</h3>
                <p>Temperature: ${data.main.temp}&#8451;</p>
                <p>Weather: ${data.weather[0].description}</p>
                <p>Humidity: ${data.main.humidity}%</p>
                <p>Wind Speed: ${data.wind.speed} m/s</p>
            `;
    })
    .catch((error) => console.error("Error fetching weather:", error));
}

function fetchForecast(city) {
  fetch(
    `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric`
  )
    .then((response) => response.json())
    .then((data) => {
      let forecastHTML = "";
      for (let i = 0; i < data.list.length; i += 8) {
        let day = data.list[i];
        forecastHTML += `
                    <div class="forecast-item">
                        <p>${new Date(day.dt_txt).toDateString()}</p>
                        <p>Temp: ${day.main.temp}&#8451;</p>
                        <p>${day.weather[0].description}</p>
                    </div>
                `;
      }
      document.getElementById("forecast").innerHTML = forecastHTML;
    })
    .catch((error) => console.error("Error fetching forecast:", error));
}
