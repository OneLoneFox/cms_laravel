var dashboard_mixin = {
    el: '#app',
    delimiters: ['[[', ']]'],
    data: function () {
        return {
            showMenu: false,
        }
    },
    methods: {
        openNotification: function(position = null, color, title, message) {
            const noti = this.$vs.notification({
                color,
                position,
                title: title,
                text: message
            });
        },
        getCookie: function (name) {
            let cookieValue = null;
            if (document.cookie && document.cookie !== '') {
                let cookies = document.cookie.split(';');
                for (var i = 0; i < cookies.length; i++) {
                    let cookie = cookies[i].trim();
                    // Does this cookie string begin with the name we want?
                    if (cookie.substring(0, name.length + 1) === (name + '=')) {
                        cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                        break;
                    }
                }
            }
            return cookieValue;
        },
        closeProfileMenu: function () {
            this.showMenu = false;
        }
    },
}