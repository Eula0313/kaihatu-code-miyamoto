const imageThumbnail = Vue.component('image-thumbnail', {
    props: {
        path: {
            type: String,
            default: ''
        }
    },
    template: `<div class="img-box" :style="{backgroundImage: 'url(' + path + ')'}" @click="$emit('clickimage')"></div>`
});
 
const app = new Vue({
    el: '#app',
    components: {
        'image-thumbnail': imageThumbnail
    },
    data() {
        return {
            isShown: false,
            selectedImage: '',
            images: [
                { path: './img/1.jpg' },
                { path: './img/2.jpg' },
                { path: './img/3.jpg' },
                { path: './img/4.jpg' },
                { path: './img/5.jpg' },
                { path: './img/6.jpg' },
                { path: './img/7.jpg' },
                { path: './img/8.jpg' },
                { path: './img/9.jpg' }
            ]
        };
    },
    methods: {
        onSelectImage(path) {
            this.selectedImage = path;
            this.isShown = true;
        },
        closeModal() {
            this.isShown = false;
        }
    }
});