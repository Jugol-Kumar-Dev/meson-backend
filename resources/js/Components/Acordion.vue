<template>
    <h1>acordion</h1>
</template>

<script setup>
    import Icon from '@/components/Icon'
    import {Inertia} from "@inertiajs/inertia";
    import {ref} from "vue";
    import PDFViewer from "@cloudpdf/viewer";
    const props = defineProps({
        chapters:{
            type:Array,
            required:true
        },
        actions:{
            type:Boolean,
            default:false,
        }
    })

    let avticationStatus = (value, status) => {
        Inertia.post(`/panel/lesson/activation/${value}?show_status=${status}`, {
            onSuccess: () => {
                //
            }
        })
    }

    // show files and videows

    let vimeo_title = ref('')
    let file_name = ref('');
    const viewer = ref(null)
    let playVideo = (url, title) => {
        vimeo_title.value = title
        document.querySelector("#attatchedVideo").innerHTML = url;
        document.getElementById('showVideo').$vb.modal.show()
    }
    let showFile = (content, title) => {
        vimeo_title.value = title;
        document.getElementById('showContent').innerHTML = content;
        document.getElementById('showFile').$vb.modal.show()
    }
    let showPDF = (pdfId) => {
        document.getElementById('webviewer').innerHTML=""
        PDFViewer(
            {
                documentId: pdfId,
                darkMode: true,
            },
            document.getElementById('webviewer')
        );

        vimeo_title.value = "show title";
        document.getElementById('showPDFfile').$vb.modal.show()
    }

    let closePdfModal = () => {
        vimeo_title.value = "";
        file_name.value = "";
        document.getElementById('showFile').$vb.modal.hide()
    }
</script>
