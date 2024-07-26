<script setup>
import { ref } from 'vue'
import SummernoteEditor from 'vue3-summernote-editor';

const myValue = ref('');
const props = defineProps({
    config:{
        type:Object,
        default:{
            height:120,
            airMode: false,
            toolbar : [
                ['insert', ['picture', 'math', 'link', 'image']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['font', ['bold', 'underline']],
            ],
            popover: {
                link: [
                    ['link', ['linkDialogShow', 'unlink']]
                ]
            }
        }
    }
})
const config = ref()



const summernoteChange = (newValue) => {
    // console.log('summernoteChange', newValue);
};

const summernoteImageLinkInsert = (...args) => {
    // console.log('summernoteImageLinkInsert()', args);
};
</script>

<template>
    <SummernoteEditor
        v-model="myValue"
        @update:modelValue="summernoteChange($event)"
        @summernoteImageLinkInsert="summernoteImageLinkInsert"
        :config="props.config"
    />
</template>
<style lang="css">

/*
.note-toolbar .note-style{
    display:none !important;
}
.note-toolbar .note-font{
    display:none !important;
}
.note-toolbar .note-fontname{
    display:none !important;
}
.note-toolbar .note-color-all{
    display:none !important;
}

.note-toolbar .note-view{
    display:none !important;
}
.note-toolbar .note-table{
    display:none !important;
}
.note-toolbar .note-para{
    display:none !important;
}
.note-editor .card-header{
    display: none !important;
}
*/

</style>
