
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
    instractors: Object,
    url: String,
    course:Object,
    errors:Object,
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
    instractors: props?.course?.instractors,
    inclues: props?.course?.inclues,
    features: props?.course?.features,
    faqs: props?.course?.faqs
})

let updateCourse = (id) => {
    createForm.post("/update-courses/"+props.course?.id, {
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
            title:null
        }
    )
}
const remoteItem = (index) => createForm.inclues.splice(index, 1);
const removeFaq = (index) => createForm.faqs.splice(index, 1);
const removeFeature = (index) => createForm.features.splice(index, 1);

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
        <form @submit.prevent="updateCourse">
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
                                <!--                        <div class="col-4">
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1">
                                        <label class="form-label">Haven with this couse
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
                                    <label class="form-label">Frequend Ask Questions</label>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="border p-1" v-for="(item, index) in createForm.faqs">
                                            <div class="">
                                                <Text class="w-100 mb-0" v-model="createForm.faqs[index].question" :error="props.errors.name"
                                                      placeholder="Course title" />
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
                    <button type="submit" class="btn btn-primary">Update Course</button>
                    <button type="reset" class="btn btn-danger">Reset</button>
                </div>
            </div>
        </form>
    </Layout>
</template>
