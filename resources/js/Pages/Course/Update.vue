
<script setup>
import Text from '@/Components/form/Text.vue';
import Image from '@/Components/form/Image.vue';
import Textarea from '@/Components/form/Textarea.vue';
import TextEditor from '@/Components/TextEditor.vue';
import Datepicker from 'vue3-datepicker'
import {useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

let props = defineProps({
    categories: Object,
    url: String,
    course:Object,
    errors:Object,
    //   can: Object,
})

let createForm = useForm({
    name: props.course.name,
    category_id: props.course.category_id,
    cover: props.course.cover,
    video: props.course.video,
    description: props.course.description,
    content: props.course.content,
    price: props.course.price,
    active_on: props.course.active_on,
    access_time:props.course.access_time,
    access_type:props.course.access_type,
    files:props.course.files,

})

let updateCourse = (id) => {
    createForm.post("/update-courses/"+id, {
        onSuccess: () => {
            createForm.reset()
        }
    })
}


</script>


<template>

    <Layout>
        <Head title="Edit Course" />
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-start mb-0">Edit Course</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="course-create-form">
            <div class="card">
                <div class="card-body">
                    <form class="form form-vertical" @submit.prevent="updateCourse(props.course.id)">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label">Select a Category</label>
                                    <v-select v-model="createForm.category_id" label="name" :options="categories" :reduce="category => category.id"></v-select>
                                </div>
                            </div>
                            <div class="col-12">
                                <Text v-model="createForm.name" :error="props.errors.name" label="Course Title" placeholder="Course title" />
                            </div>
<!--                            <div class="col-4">
                                <Image v-model="createForm.cover" :error="props.errors.cover" label="Cover Pic"/>
                            </div>-->
<!--                            <div class="col-4">
                                <Text v-model="createForm.video" :error="props.errors.video" label="Course Intro Video-(Only URL)" placeholder="Course Intro Video Url" />
                            </div>
                            <div class="col-4">
                                <Image v-model="createForm.files" label="Others Files"/>
                            </div>-->
                            <div class="col-12">
                                <Textarea v-model="createForm.description" :error="props.errors.description" label="Course Short Description- max(300)" />
                            </div>
<!--                            <div class="col-12">
                                <div class="mb-1">
                                    <label class="form-label">Course content</label>
                                    <TextEditor v-model="createForm.content" />
                                </div>
                            </div>-->
                            <div class="col-4">
                                <Text v-model="createForm.price" type="number" label="Course Price" placeholder="100.00" prefix="BDT" />
                            </div>
<!--                            <div class="col-4">
                                <label>Active From: </label>
                                <datepicker v-model="createForm.active_on" class="form-control" placeholder="Choose a date" />
                                <span class="error text-danger" v-if="props.errors.active_on">{{ props.errors.active_on }}</span>
                            </div>
                            <div class="col-4">
                                <label>Access Time:</label>
                                <fieldset>
                                    <div class="input-group">
                                        <input type="text" v-model="createForm.access_time" class="form-control" placeholder="Access Limitation Time" aria-label="Amount">
                                        <select class="form-control"  v-model="createForm.access_type" placeholder="Chose Access Type">
                                            <option selected value="">~~ Chose Option ~~</option>
                                            <option value="Year">Year</option>
                                            <option value="Month">Month</option>
                                            <option value="Days">Days</option>
                                        </select>

                                    </div>
                                </fieldset>
                            </div>-->

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
