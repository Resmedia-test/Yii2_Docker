const objHours = document.getElementById('timeNow');

const nameMonth = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];
const nameDay = ['Воскресенье', 'Понедельник', 'Вторник', 'Седа', 'Четверг', 'Пятница', 'Суббота'];
/**
 * @author Evgenii Rogozhuk
 * 
 * @function
 * @name getTimeNow
 * @description Function for get time now Russian
 * @returns {string} 
* */
function getTimeNow() {
    const time = new Date();

    let timeSec = time.getSeconds();
    let timeMin = time.getMinutes();
    let timeHours = time.getHours();
    let timeNew = ((timeHours < 10) ? '0' : '') + timeHours;

    timeNew += ':';
    timeNew += ((timeMin < 10) ? '0' : '') + timeMin;
    timeNew += ':';
    timeNew += ((timeSec < 10) ? '0' : '') + timeSec + ' ';

    timeNew = nameDay[time.getDay()] + ', ' + time.getDate() + ' ' + nameMonth[time.getMonth()] + ' ' + time.getFullYear() + ', ' + timeNew;

    objHours.innerHTML = timeNew;
}

getTimeNow();
setInterval('getTimeNow()', 1000);