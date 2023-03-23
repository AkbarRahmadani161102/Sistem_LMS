const refreshTime = () => {
    const currentTime = new Date()
    const dayArr = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu',]
    const timeString = `${dayArr[currentTime.getDay()]} ${currentTime.toLocaleTimeString()}`
    $('#live-clock').text(timeString)
}

refreshTime()
setInterval(() => {
    refreshTime()
}, 60000)