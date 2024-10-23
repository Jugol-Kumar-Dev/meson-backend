<template>

    <Layout>
        <Head title="Create Course"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="float-start mb-0">Create Course</h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="course-create-form">
            <form @submit.prevent="createNewCourse">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Select a Category</label>
                                            <v-select v-model="createForm.category_id" label="name"
                                                      :options="categories"
                                                      :reduce="category => category.id"></v-select>
                                            <span class="error text-danger"
                                                  v-if="props.errors.category_id">{{ props.errors.category_id }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <Text v-model="createForm.name" :error="props.errors.name" label="Course Title"
                                              placeholder="Course title"/>
                                    </div>
                                    <div class="col-6">
                                        <label>Access Time:</label>
                                        <fieldset>
                                            <div class="input-group text-black">
                                                <input type="text" v-model="createForm.access_time" class="form-control"
                                                       placeholder="Access Limitation Time" aria-label="Amount">
                                                <select class="form-control text-black" v-model="createForm.access_type"
                                                        placeholder="Chose Access Type">
                                                    <option selected disabled value="">~~ Chose Option ~~</option>
                                                    <option value="Year">Year</option>
                                                    <option value="Month">Month</option>
                                                    <option value="Days">Days</option>
                                                </select>
                                            </div>
                                        </fieldset>
                                    </div>
                                    <div class="col-6">
                                        <Image v-model="createForm.cover" :error="props.errors.cover"
                                               label="Cover Pic"/>
                                    </div>
                                    <div class="col-12">
                                        <Textarea v-model="createForm.description" :error="props.errors.description"
                                                  label="Course Short Description- max(300)"/>
                                    </div>
                                    <div class="col-6">
                                        <Text v-model="createForm.price" type="number" label="Course Price"
                                              :error="props.errors.price" placeholder="100.00" prefix="BDT"/>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <label class="form-label">Select Instractors</label>
                                            <v-select v-model="createForm.instractors" multiple="true" label="name"
                                                      :options="instractors"
                                                      :reduce="item => item.id"></v-select>
                                            <span class="error text-danger"
                                                  v-if="props.errors.category_id">{{ props.errors.category_id }}</span>
                                        </div>
                                    </div>
                                    <!--                                    <div class="col-4">
                                                                            <label>Active From: </label>
                                                                            <datepicker v-model="createForm.active_on" class="form-control"
                                                                                        placeholder="Choose a date"/>
                                                                            <span class="error text-danger"
                                                                                  v-if="props.errors.active_on">{{ props.errors.active_on }}</span>
                                                                        </div>-->

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label class="form-label">
                                                Included this course
                                                <a href="https://icones.js.org/" target="_blank" title="Get Icon name">
                                                    <vue-feather type="external-link" size="10"/>
                                                </a>
                                            </label>
                                            <div class="d-flex justify-content-between align-items-start"
                                                 v-for="(item, index) in createForm.inclues">
                                                <Text v-model="createForm.inclues[index].icon"
                                                      :error="props.errors?.inclues" placeholder="icon"/>


                                                <Text v-model="createForm.inclues[index].title"
                                                      :error="props.errors.inclues" placeholder="title"/>


                                                <button type="button" class="btn btn-sm btn-primary" v-if="index === 0"
                                                        @click="addRow">
                                                    +
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" v-else
                                                        @click="remoteItem(index)">
                                                    -
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-12">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <Text v-model="createForm.video"
                                                      :error="props.errors.video"
                                                      label="Course Intro Video-(Youtube Video Id)" placeholder="Course Intro Video Url" />
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1">
                                            <label class="form-label">Course Features</label>
                                            <div class=""
                                                 v-for="(item, index) in createForm.features">

                                                <Textarea v-model="createForm.features[index].title"
                                                          :error="props.errors.description"/>


                                                <button type="button" class="btn btn-sm btn-primary"
                                                        v-if="index === 0" @click="addFeature">
                                                    +
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary" v-else
                                                        @click="removeFeature(index)">
                                                    -
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="form-label">Frequently Asked Questions</label>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="border p-1" v-for="(item, index) in createForm.faqs">
                                                <div class="">
                                                    <Text class="w-100 mb-0" v-model="createForm.faqs[index].question"
                                                          :error="props.errors.name"
                                                          placeholder="Course title"/>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-primary"
                                                                v-if="index === 0" @click="addFaq">
                                                            +
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-secondary" v-else
                                                                @click="removeFaq(index)">
                                                            -
                                                        </button>

                                                    </div>

                                                </div>
                                                <Textarea class="mt-0" v-model="createForm.faqs[index].answer"
                                                          :error="props.errors.description"/>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Course</button>
                        <button type="reset" class="btn btn-danger">Reset</button>
                    </div>
                </div>
            </form>
        </section>
    </Layout>
</template>

<script setup>
import Text from '@/Components/form/Text.vue';
import Image from '@/Components/form/Image.vue';
import Textarea from '@/Components/form/Textarea.vue';
import Layout from "@/Shared/Layout.vue";
import {useForm} from "@inertiajs/vue3";

let props = defineProps({
    categories: Object,
    url: String,
    instractors: Array,
    errors: Object,
})

let createForm = useForm({
    name: null,
    category_id: null,
    cover: null,
    video: null,
    description: null,
    content: null,
    price: null,
    active_on: null,
    files: null,
    access_time: null,
    access_type: '',
    instractors: [],
    inclues: [
        {
            icon: null,
            title: null
        },
    ],
    features: [{title: null}],
    faqs: [
        {
            question: null,
            answer: null
        }
    ]
})

let createNewCourse = () => {
    createForm.post(props.url, {
        onSuccess: () => {
            createForm.reset()
        }
    })
}


const addRow = () => {
    createForm.inclues.push(
        {
            icon: null,
            title: null
        }
    )
}

const addFaq = () => {
    createForm.faqs.push(
        {
            question: null,
            answer: null
        }
    )
}
const addFeature = () => {
    createForm.features.push(
        {
            title: null
        }
    )
}
const remoteItem = (index) => createForm.inclues.splice(index, 1);
const removeFaq = (index) => createForm.faqs.splice(index, 1);
const removeFeature = (index) => createForm.features.splice(index, 1);

</script>
