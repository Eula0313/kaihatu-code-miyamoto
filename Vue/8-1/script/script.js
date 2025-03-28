const imageThumbnail = Vue.component('image-thumbnail', {
    props: {
        path: {
            type: String,
            default: ''
        }
    },
    template: '<div class="img-box" :style="{backgroundImage: \'url(\' + path + \')\'}" @click="$emit(\'clickimage\')"></div>'
});

const app = new Vue({
    el: '#app',
    components: {
        'image-thumbnail': imageThumbnail
    },
    data() {
        return {
            images:[{
                path: './img/img1.jpg',
            },{
                path: './img/img2.jpg',
            },{
                path: './img/img3.jpg',
            },{
                path: './img/img4.jpg',
            },{
                path: './img/img5.jpg',
            },{
                path: './img/img6.jpg',
            },{
                path: './img/img7.jpg',
            },{
                path: './img/img8.jpg',
            },{
                path: './img/img9.jpg',

            }]
        };
    }
});