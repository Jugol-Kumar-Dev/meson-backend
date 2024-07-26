<template>

    <Layout>
        <Head title="Create Question" />
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-start mb-0">Create Question</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="question-create-form">
        <div class="card">
            <div class="card-body">
                <form class="form form-vertical" @submit.prevent="createNewCategory">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="mb-1">
                                <label class="form-label">Select a Category</label>
                                <v-select
                                    v-model="createForm.category_id"
                                  class="bg-white"
                                  label="name"
                                  :options="props.categories"
                                  :reduce="cat => cat.id"
                                  placeholder="Please select a category for question sorting">
                                </v-select>
                                <span class="error text-danger" v-if="props.errors.category_id">{{ props.errors.category_id }}</span>

                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="mb-1">
                                <label class="form-label">Question Mark</label>
                                <input type="number" v-model="createForm.mark" class="form-control" placeholder="Question Mark">
                                <span class="error text-danger" v-if="props.errors.mark">{{ props.errors.mark }}</span>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label fw-bolder fs-2">Question</label>
                                    <Editor v-model="createForm.title"/>
                                    <span class="error text-danger" v-if="props.errors.title">{{ props.errors.title }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bolder fs-2">Answer A</label>
                                    <Editor v-model="createForm.a"/>
                                    <span class="error text-danger" v-if="props.errors.a">{{ props.errors.a }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bolder fs-2">Answer B</label>
                                    <Editor v-model="createForm.b"/>
                                    <span class="error text-danger" v-if="props.errors.b">{{ props.errors.b }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bolder fs-2">Answer C</label>
                                    <Editor v-model="createForm.c"/>
                                    <span class="error text-danger" v-if="props.errors.c">{{ props.errors.c }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bolder fs-2">Answer D</label>
                                    <Editor v-model="createForm.d"/>
                                    <span class="error text-danger" v-if="props.errors.d">{{ props.errors.d }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bolder fs-2">Correct Answer</label>
                            <Radio :error="props.errors.answer"  v-model="createForm.answer" :options="options" name="answer"/>
                        </div>
                        <div class="col-12 mt-5">
                            <div class="mb-1">
                                <label class="form-label fw-bolder fs-2">Explanation</label>
                                <Editor v-model="createForm.feedback"/>
                                <span class="error text-danger" v-if="props.errors.feedback">{{ props.errors.feedback }}</span>
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
import Radio from '@/Components/form/Radio.vue';
import {useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import Editor from "@/Components/Editor.vue";
let props = defineProps({
    categories: Array,
    url: String,
    subbycat_url: String,
    childbysubcat_url: String,
    errors:Object,
    //   can: Object,
});

let createForm = useForm({
    title: '',
    category_id: '',
    a: '',
    b: '',
    c: '',
    d: '',
    e: '',
    answer: '',
    mark:1,
    feedback: '',
});
let options = {a: 'Option A', b: 'Option B', c: 'Option C', d: 'Option D'}



let createNewCategory = () => {
    createForm.post(props.url, {
        onSuccess: () => {
            createForm.reset()
        }
    });
}
</script>

<style>
.note-editable{
    min-height: 200px !important;
}
</style>
