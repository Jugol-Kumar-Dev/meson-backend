<template>

    <Layout>
        <Head title="Create Course" />
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Page Create</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="course-create-form">
            <div class="card">
                <div class="card-body">
                    <form class="form form-vertical" @submit.prevent="createNewCourse">
                        <div class="row">
                            <div class="col-12">
                                <Text v-model="createForm.title" :error="props.errors.title" label="Page Title" placeholder="Page title" />
<!--                                <Text v-model="createForm.slug" :error="props.errors.slug" label="Page Slogan" placeholder="Page Slogan" />-->
                            </div>
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label">Page content</label>
                                    <SummernoteEditor
                                        v-model="createForm.content"
                                    />
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit"
                                        class="btn btn-primary me-1 waves-effect waves-float waves-light">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </Layout>
</template>

<script setup>
import Layout from "@/Shared/Layout.vue";
import {useForm} from "@inertiajs/vue3";
import SummernoteEditor from 'vue3-summernote-editor';
import Text from "@/Components/form/Text.vue";
let props = defineProps({
    url: String,
    errors:Object,
})

let createForm = useForm({
    title: null,
    content: null,
    // slug: null,
})

let createNewCourse = () => {
    createForm.post(props.url, {
        onSuccess: () => {
            createForm.reset()
        }
    })
}


</script>


<style>
.note-editing-area .note-editable{
    min-height: 400px;
}
</style>
