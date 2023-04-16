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
    const urlFilename = url.substring(url.lastIndexOf('/') + 1).match(/(.*.php)/)[0]

    $('nav#dashboard-sidebar a').each(function () {
        const href = $(this).attr('href')
        href
            && href.match(/(.*)\/(.*.php)(.*)/)[2] === urlFilename
            && $(this).addClass('active')
    })

    $('#check_all').on('click', function (e) {
        let condition = this.checked
        if (condition) {
            $("input[name='delete_pertemuan[]']").each(function () {
                this.checked = true
            })
        } else {
            $("input[name='delete_pertemuan[]']").each(function () {
                this.checked = false
            })
        }
    })

    $('select.selectize').selectize({
        onFocus: function () {
            $(this)[0].clear()
        }
    })

    $('select.selectize.group').selectize({
        sortField: 'text'
    })

    $('.datatable').DataTable();

})

let themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
let themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
let themeToggleDarkIconText = document.getElementById('theme-toggle-dark-icon-text');
let themeToggleLightIconText = document.getElementById('theme-toggle-light-icon-text');

// Change the icons inside the button based on previous settings
if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    themeToggleDarkIcon.classList.remove('hidden');
    themeToggleDarkIconText.classList.remove('hidden')
} else {
    themeToggleLightIcon.classList.remove('hidden');
    themeToggleLightIconText.classList.remove('hidden')
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