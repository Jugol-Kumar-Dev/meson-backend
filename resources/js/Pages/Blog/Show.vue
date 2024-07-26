

<script setup>
import {computed, ref} from 'vue'
import Icon from '@/Components/Icon.vue'
import Modal from '@/Components/Modal.vue'
import Text from '@/Components/form/Text.vue'
import Textarea from '@/Components/form/Textarea.vue'
import Image from '@/Components/form/Image.vue';
import TextEditor from '@/Components/TextEditor.vue';
import axios from "axios";
import Swal from 'sweetalert2'
import {router, useForm} from "@inertiajs/vue3";
let props = defineProps({
    course: Object,
    lessons: Object,
    url: String,
    lesson_store_url: String,
    mocktests_create:String,
    save_mocktest:String,
    mocktests:Object,
    errors:Object,
    lesson_index:String,
});

let createForm = useForm({
    name: null,
    course_id: props.course.id,
    description: null,
    video: null,
    status: false,
    file:null,
    content:null,
});
let createNewLesson = () => {
    createForm.post(props.lesson_store_url, {
        onSuccess: () => {
            document.getElementById('createNewLesson').$vb.modal.hide()
            createForm.reset()
        }
    });
}

let updateLesson = useForm({
    name: null,
    course_id: props.course.id,
    description: null,
    video: null,
    status: null,
    file:null,
    content:null,
})

let editLesson = ref({});

let editCategory = (id) => {
    axios.get(props.lesson_index+"/"+id+"/edit").then(res => {
        editLesson.value = res.data.id
        updateLesson.id = res.data.id;
        updateLesson.name = res.data.name;
        updateLesson.description = res.data.description;
        updateLesson.video = res.data.video;
        updateLesson.status = res.data.status === 1 ? true : false;
        updateLesson.file = res.data.file;
        updateLesson.content = res.data.content;
        document.getElementById('updateLesson').$vb.modal.show()
    }).catch(err => {
        console.log(err.data)
    })
}

let updateSingleLesson = (id) =>{
    updateLesson.post(props.lesson_index+"/"+id+"/update", {
        onSuccess: ()=>{
            document.getElementById('updateLesson').$vb.modal.hide()
            updateLesson.reset()
        }
    })
}


let updateForm = useForm({
    mocktest_id:'',
    status:'',
})
let saveMocktest = () => {
    updateForm.put(props.save_mocktest, {
        onSuccess: () => {
            document.getElementById('addMocktest').$vb.modal.hide()
            createForm.reset()
        }
    });
}

let mainMocktest = computed(()=>{
    return new Set(props.course.mocktests.map(single => single.name));

    // function getUniqueListBy(arr, key) {
    //     return [...new Map(arr.map(item => [item.key, item])).values()]
    // }
    //
    // return getUniqueListBy(props.course.mocktests, 'name')

})

let deleteItemModal = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(props.lesson_index+"/" + id, {
                preserveState: true, replace: true, onSuccess: page => {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                },
                onError: errors => {
                    Swal.fire(
                        'Oops...',
                        'Something went wrong!',
                        'error'
                    )
                }
            })
        }
    })
};

let avticationStatus = (value, status) => {
    router.post(`/panel/lesson/activation/${value}?show_status=${status}`, {
        onSuccess: () => {
            //
        }
    })
}

let vimeo_title = ref('')
let file_name = ref('');
let playVideo = (url, title) => {
    vimeo_title.value = title
    document.querySelector("#attatchedVideo").innerHTML = url;
    document.getElementById('showVideo').$vb.modal.show()
}
// let showFile = (url, title) => {
//     vimeo_title.value = title;
//     file_name.value = url;
//     var head = $("#myiframe").contents().find("head");
//     var css = '<style type="text/css">' +
//         '.ndfHFb-c4YZDc-Wrql6b{display:none}; ' +
//         '</style>';
//     $(head).append(css);
//
//     document.getElementById('showFile').$vb.modal.show()
// }
let showFile = (content, title) => {
    vimeo_title.value = title;
    document.getElementById('showContent').innerHTML = content;
    document.getElementById('showFile').$vb.modal.show()
}

let closePdfModal = () => {
    vimeo_title.value = "";
    file_name.value = "";
    document.getElementById('showFile').$vb.modal.hide()
}

let changeStatus = (event) =>{
    axios.get(`/update-course-status/${event.target.value}/${props.course.id}`).then(res => {
        Swal.fire(
            'Success!',
            'Lesson has been updated ....:).',
            'success'
        )
    }).catch(err =>{
        console.log(err);
    })


}


// webView.loadUrl("javascript:(function() {document.querySelector('[class=\"ndfHFb-c4YZDc-Wrql6b\"]').remove();})()");


</script>


<template>
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center w-75">
                        <div class="w-100 me-3">
                            <label>Change Status</label>
                            <select class="form-select me-3" @change="changeStatus($event)">
                                <option>~~ Select Status ~~</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div>
                            <label>Current Status</label>
                            <span v-if="course.status == 'active'" class="badge badge-light-success">{{ course.status }}</span>
                            <span v-else class="badge badge-light-warning">{{ course.status }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <img class="img-fluid rounded mb-75"
                        :src="course.cover"
                        :alt="course.name" width="150" />
                    <div class="d-flex justify-content-between align-items-center mb-1 bg-light px-1 pt-1 rounded">
                        <div class="h5">
                            Category: {{ course.category_id }}
                        </div>
                        <div class="h5">
                            Price: {{ course.price }} BDT
                        </div>
                        <div class="h5">
                            Start From: {{ course.active_on }}
                        </div>
                    </div>

                    <div class="h3">
                        {{ course.name }}
                    </div>

                    <!-- video -->
<!--                    <div class="w-100 rounded border-0 height-250 mb-50 ifremClass" v-html="course.video"></div>-->
                    <!--/ video -->
                    <h3 class="border-bottom-primary d-inline">Course Short Details :</h3>
                    <p class="card-text mt-2" style="text-align: justify" v-html="course.description"></p>
                    <hr>
                    <h3 class="border-bottom-primary d-inline">Course Details :</h3>
                    <p class="mt-2" v-html="course.content"></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h4>All Lesson List</h4>
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                            data-bs-target="#createNewLesson">Create New Lesson</button>
                </div>
                <table class="user-list-table table">
                    <thead class="table-light">
                    <tr class="">
                        <th class="sorting">SL.</th>
                        <th class="sorting">Title</th>
                        <th class="sorting">Status</th>
                        <th class="sorting">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(lesson, index) in lessons" :key="lesson.id">
                        <td>#{{ index+1 }}</td>
                        <td>
                            <small>{{ lesson.name }}</small>
                        </td>
                        <td>
                            <span v-if="lesson.status == '1'" class="badge badge-light-primary">Active</span>
                            <span v-else class="badge badge-light-warning">Draft</span>
                        </td>
                        <td>

                            <div class="btn-group dropup dropdown-icon-wrapper">
                                <button type="button"
                                        class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                </button>

                                <div class="dropdown-menu">
                                    <span class="dropdown-item"
                                          v-if="lesson.status == 1"
                                          @click="avticationStatus(lesson.id, false)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                        <span class="ms-1">Inactive</span>
                                    </span>
                                    <span class="dropdown-item"
                                          v-else
                                          @click="avticationStatus(lesson.id, true)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                        <span class="ms-1">Active</span>
                                    </span>

                                    <span v-if="lesson.content != null" class="dropdown-item"
                                          @click="showFile(lesson.content, lesson.name)">
                                        <Icon title="eye"/>
                                       <span class="ms-1">File</span>
                                    </span>

                                    <span v-if="lesson.video" class="dropdown-item"
                                          @click="playVideo(lesson.video, lesson.name)">
                                        <Icon title="play-circle"/>
                                       <span class="ms-1">Show</span>
                                    </span>

                                    <span class="dropdown-item"
                                          @click="editCategory(lesson.id)">
                                        <Icon title="pencil"/>
                                        <span class="ms-1">Edit</span>
                                    </span>

                                    <span class="dropdown-item"
                                          @click="deleteItemModal(lesson.id)">
                                        <Icon title="trash"/>
                                       <span class="ms-1">Delete</span>
                                    </span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h4>All Exam</h4>
                    <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                            data-bs-target="#addMocktest">Add New Exam</button>
                </div>
                <table class="user-list-table table">
                    <thead class="table-light">
                    <tr class="">
                        <th class="sorting">SL.</th>
                        <th class="sorting">Title</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(mocktest, index) in mainMocktest" :key="mocktest.id">
                        <td>#{{ index+1 }}</td>
                        <td>
                            <small>{{ mocktest }}</small>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <figure class="media"><oembed url="https://www.youtube.com/watch?v=3PX0brdmsWE"></oembed></figure>

        </div>
    </div>
    <NewEditor/>

    <Modal id="createNewLesson" title="Create New Lesson" v-vb-is:modal size="lg">
        <form @submit.prevent="createNewLesson">
            <div class="modal-body">
                <Text v-model="createForm.name" type="text" label="Lesson Title" :error="createForm.errors.name" placeholder="Lesson title" />
                <span class="text-danger text-small" v-if="errors.course_id">{{ errors.mocktest_id }}</span>

                <Text v-model="createForm.video" label="Lesson Video- (embed-url)" placeholder="Lesson Video Url" />
                <Image v-model="createForm.file" label="Chose Related File" placeholder="Add Related Files"/>
                <Textarea v-model="createForm.description" label="Lesson Description"
                    :error="createForm.errors.description" placeholder="Lesson description" />

                <label class="form-label">Course content</label>
                <TextEditor v-model="createForm.content" />

                <label class="form-check-label mb-50" for="customSwitch111">Lesson Status: </label>
                <div class="form-check form-switch form-check-success">
                    <input type="checkbox" class="form-check-input" id="customSwitch111" v-model="createForm.status" :true-value="true" :false-value="false" />
                    <label class="form-check-label" for="customSwitch111">
                        <span class="switch-icon-left">
                            <Icon title="check" />
                        </span>
                        <span class="switch-icon-right">
                            <Icon title="x" />
                        </span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                    class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                    aria-label="Close">Cancel</button>
            </div>
        </form>
    </Modal>

    <Modal id="updateLesson" title="Update Lesson" v-vb-is:modal size="lg">
        <form @submit.prevent="updateSingleLesson(updateLesson.id)">
            <div class="modal-body">
                <Text v-model="updateLesson.name" type="text" label="Lesson Title" :error="updateLesson.errors.name" placeholder="Lesson title" />
                <span class="text-danger text-small" v-if="updateLesson.course_id">{{ errors.mocktest_id }}</span>

                <Text v-model="updateLesson.video" label="Lesson Video- (embed-url)" placeholder="Lesson Video Url" />
                <Image v-model="updateLesson.file" label="Chose Related File" placeholder="Add Related Files"/>
                <Textarea v-model="updateLesson.description" label="Lesson Description"
                          :error="createForm.errors.description" placeholder="Lesson description" />

                <label class="form-label">Course content</label>
                <TextEditor v-model="updateLesson.content" />
                <label class="form-check-label mb-50" for="lessonStatus">Lesson Status: </label>
                <div class="form-check form-switch form-check-success">
                    <input type="checkbox" class="form-check-input" id="lessonStatus" v-model="updateLesson.status" :true-value="true" :false-value="false" />
                    <label class="form-check-label" for="lessonStatus">
                        <span class="switch-icon-left">
                            <Icon title="check" />
                        </span>
                        <span class="switch-icon-right">
                            <Icon title="x" />
                        </span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
            </div>
        </form>
    </Modal>


    <Modal id="addMocktest" title="Add Exam In Course" v-vb-is:modal>
        <form @submit.prevent="saveMocktest">
            <div class="modal-body">
                <label>Exam: <small>(if not exist)</small></label><br>

                <div class="mb-1">
                    <v-select v-model="updateForm.mocktest_id" label="name"
                              :options="mocktests"
                              :reduce="mocktest => mocktest.id">

                    </v-select>
                    <span class="text-danger text-small" v-if="errors.mocktest_id">{{ errors.mocktest_id }}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button :disabled="createForm.processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
            </div>
        </form>
    </Modal>

    <Modal id="showVideo" size="lg" title="Show Course Video" v-vb-is:modal>
            <div class="modal-body">
                <div class="w-100 h-100" id="attatchedVideo"></div>
            </div>
    </Modal>

    <Modal id="showFile" size="lg" title="Show Course Video" v-vb-is:modal>
        <div class="modal-body" id="showContent">
        </div>
    </Modal>

    <div class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ vimeo_title }}</h4>
                    <button type="button" class="btn-close" @click="closePdfModal" aria-label="Close"></button>
                </div>
                <!--<div class="modal-body position-relative">
                    <div class="body-overlay"></div>
                    <iframe id="myiframe"
                        :src="file_name"
                        frameborder="0" class="w-100" style="height: 70vh;">
                    </iframe>
                    <div class="bottom-iframe"></div>
                </div>-->
            </div>
        </div>
    </div>

</template>
<style scoped>
table td,
th {
    padding: 0.5rem;
}

</style>
