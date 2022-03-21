import Vue from 'vue';
import moment from 'moment-timezone';

function numberFormat(value) {
    var number = parseInt(value);
    if(isNaN(number) || number == null) {
        return 0;
    }
    var formatter = new Intl.NumberFormat('it-IT');
    return formatter.format(number);
}

function limitString(value) {
    if(value.length < 20) {
        return value;
    } else {
        return value.substring(0, 20) + '...';
    }
}

function limitString30(value) {
    if(value.length < 40) {
        return value;
    } else {
        return value.substring(0, 40) + '...';
    }
}

function limitString45(value) {
    if(value.length < 45) {
        return value;
    } else {
        return value.substring(0, 45) + '...';
    }
}

function limitString50(value) {
    if(value.length < 50) {
        return value;
    } else {
        return value.substring(0, 50) + '...';
    }
}

function limitString60(value) {
    if(value.length < 60) {
        return value;
    } else {
        return value.substring(0, 60) + '...';
    }
}

function limitString100(value) {
    if(value.length < 100) {
        return value;
    } else {
        return value.substring(0, 100) + '...';
    }
}

function formatUnix (date) {
    return moment(date).format('DD/MM/YYYY');
}

function formatTimeLogUnix (date) {
    if(date == 'null' || date == undefined){
        return '-';
    }
    return moment(date).format('HH:mm DD/MM/YYYY');
}
function formatDateTime (date) {
    if(date === undefined){
        return '-';
    }
    return moment(date).utc().format('HH:mm DD/MM/YYYY');
}

function formatTime (date) {
    return moment(date).utc().format('HH:mm');
}

function totalPoint (items) {
    let total = 0;
    items.forEach(item => {
        total = total + item.point;
    })
    return total;
}


function getLabelTypeGift(type) {
    console.log(type)
    switch (type) {

        case "EMPTY":
            return "Chúc bạn may mắn"
        case "BONUS_TURN":
            return "Thêm lượt"
        case "COUPON":
            return "Mã giảm giá"
        case "WALLET":
            return "Cộng tiền vào ví thưởng"
        case "PIECE":
            return "Mảnh ghép"
        default:
            return "-"
    }
}

function getLabelStatusRunningGame(status) {
    console.log(status)
    let label = '';
    if (status & 1) {
        label += ` <span class="label label-sm bg-success text-white mr-1">Start</span>`
    }
    if (status & 2) {
        label += ` <span class="label label-sm bg-danger text-white mr-1">Error</span>`
    }
    if (status & 4) {
        label += ` <span class="label label-sm bg-success text-white mr-1">End</span>`
    }
    if (status & 8) {
        label += ` <span class="label label-sm bg-success text-white mr-1">EXCHANGE SUCCESS</span>`
    }
    if (status & 16) {
        label += ` <span class="label label-sm bg-danger text-white mr-1">EXCHANGE ERROR</span>`
    }
    return label;
}

function pretty (value) {
    return JSON.stringify(value);
}

Vue.filter('limitString', limitString);
Vue.filter('numberFormat', numberFormat);
Vue.filter('formatUnix', formatUnix);
Vue.filter('formatTimeLogUnix', formatTimeLogUnix);
Vue.filter('formatDateTime', formatDateTime);
Vue.filter('formatTime', formatTime);
Vue.filter('limitString30', limitString30);
Vue.filter('limitString45', limitString45);
Vue.filter('limitString50', limitString50);
Vue.filter('limitString60', limitString60);
Vue.filter('limitString100', limitString100);
Vue.filter('getLabelTypeGift', getLabelTypeGift);
Vue.filter('totalPoint', totalPoint);
Vue.filter('getLabelStatusRunningGame', getLabelStatusRunningGame);
Vue.filter('pretty', pretty);
