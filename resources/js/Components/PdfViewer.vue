<template>
    <div id="webviewer" ref="viewer"></div>
</template>
<script>
    import PDFViewer from "@cloudpdf/viewer";
    export default {
        name: "PDFViewer",
        props: {
            id: String,
        },
        mounted() {
            PDFViewer(
                {
                    documentId: this.id,
                    darkMode: true,
                },
                this.$refs.viewer
            );
        },
    };
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    #webviewer {
        height: 400px;
        width: 100%;
    }
</style>
