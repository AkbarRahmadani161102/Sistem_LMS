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

$(document).ready(() => {
    const url = location.href
    const urlFilename = url.substring(url.lastIndexOf('/') + 1)
    // console.log($(`a[href]`))
    $(`nav#dashboard-sidebar a[href$='${urlFilename}']`).addClass('active')
})

let themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
let themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
let themeToggleDarkIconText = document.getElementById('theme-toggle-dark-icon-text');
let themeToggleLightIconText = document.getElementById('theme-toggle-light-icon-text');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleLightIcon.classList.remove('hidden');
    themeToggleLightIconText.classList.remove('hidden')
} else {
    themeToggleDarkIcon.classList.remove('hidden');
    themeToggleDarkIconText.classList.remove('hidden')
}

const themeToggleBtn = $('#theme-toggle');

themeToggleBtn.each(function () {
    $(this).on('click', () => {
        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleDarkIconText.classList.toggle('hidden')
        themeToggleLightIcon.classList.toggle('hidden');
        themeToggleLightIconText.classList.toggle('hidden')

        // if set via local storage previously
        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }

            // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }

    })
})