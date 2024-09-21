<script setup>
import Swal from "sweetalert2";
import Modal from '@/Components/Modal.vue'
import Text from '@/Components/form/Text.vue';
import Textarea from '@/Components/form/Textarea.vue';
import Icon from '@/Components/Icon.vue'
import {ref} from 'vue'
import moment from "moment"

import {useAction} from "@/Composable/useAction.js";
import axios from "axios";
import PDFViewer from "@cloudpdf/viewer";
import {router, useForm} from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import LessonVideos from "@/Pages/Course/Partials/LessonVideos.vue";
import Mocktests from "@/Pages/Course/Partials/Mocktests.vue";
import Meetings from "@/Pages/Course/Partials/Meetings.vue";

let format = (time, formate) => {
    return moment(time).format(formate);
};
const {deleteItem} = useAction();

let props = defineProps({
    course: Object,
    lessons: Object,
    url: String,
    lesson: String,
    mocktests_create: String,
    save_mocktest: String,
    mocktests: [] | Object | null,
    errors: Object,
    lesson_index: String,
    pdfIdShowFIle: String,
    apiMocktests: [] | null,
    chapers: [] | null,
    lessonVideos: [] | null,

    liveClass: [] | Object | null,

    add_course_mitting: null,
    main_url: null,
    chapter_url: String,
    lesson_store_url: String,
    mock_delete_url: null,
});


let processing = ref(false)

let addZoom = useForm({
    zoomid: '',
    courseId: props.course.id,
})
let addCourseMitting = () => {
    addZoom.post(props.add_course_mitting, {
        onSuccess: () => {
            document.getElementById('addZoom').$vb.modal.hide()
            addZoom.reset()
            Swal.fire(
                'Success!',
                'Zoom Meeting Has Been Added ....:).',
                'success'
            )
        }
    })
}

// mock test sections
let updateForm = useForm({
    mock_id: null,
    course_id: props.course.id
})

let saveMocktest = () => {
    updateForm.put(props.save_mocktest, {
        onSuccess: () => {
            document.getElementById('addMocktest').$vb.modal.hide()
            updateForm.reset()
        }
    });
}



let createForm = useForm({
    name: null,
    course_id: props.course.id,
    chapter_id: null,
    description: null,
    video: null,
    status: false,
    file: null,
    content: null,
});
let createNewLesson = () => {
    createForm.post(props.lesson_store_url, {
        onSuccess: () => {
            document.getElementById('createNewLesson').$vb.modal.hide()
            createForm.reset()
        }
    });
}
// course status
let changeStatus = (event) => {
    axios.get(`/update-course-status/${event}/${props.course.id}`).then(res => {
        Swal.fire(
            'Success!',
            'Lesson has been updated ....:).',
            'success'
        )
        location.reload()
    }).catch(err => {
        console.log(err);
    })
}

//video and file shows


let avticationStatus = (value, status) => {
    router.post(`/lesson/activation/${value}?show_status=${status}`, {
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
    document.getElementById('webviewer').innerHTML = ""
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

// lesson edit and update

let updateLesson = useForm({
    name: null,
    course_id: props.course.id,
    chapter_id: null,
    description: null,
    video: null,
    status: null,
    file: null,
    content: null,
})
let editLesson = ref({});
let editCategory = (id) => {
    axios.get(props.lesson_index + "/" + id + "/edit").then(res => {
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
let updateSingleLesson = (id) => {
    updateLesson.post(props.lesson_index + "/" + id + "/update", {
        onSuccess: () => {
            document.getElementById('updateLesson').$vb.modal.hide()
            updateLesson.reset()
        }
    })
}


// chapter edit
const editChapterRes = ref({});
const editChapterItem = (url, id) => {
    axios.get(`${url}/${id}`).then(res => {
        editChapterRes.value = res.data;
        document.getElementById('updateNewChapter').$vb.modal.show()
    }).catch(err => {
        console.log(err);
    })
}
// const updateChapter = () => {
//     router.put(`${props.chapter_url}/${editChapterRes.value.id}`, editChapterRes.value, {
//         onSuccess: () => {
//             editChapterRes.value = {};
//             document.getElementById('updateNewChapter').$vb.modal.hide()
//         }
//     })
// }
</script>


<template>

    <Layout>

        <div class="container w-100" style="min-height: 100vh; max-width:100% !important;">
            <div class="row match-height">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start gap-3 h-100">
                                <div class="w-50">
                                    <img class="card-img rounded-2 w-100 h-100 object-fit-cover"
                                         :src="course.cover"
                                         :alt="course.name"/>
                                </div>
                                <div class="w-50 d-flex flex-column justify-content-between h-100">
                                    <div class="d-flex align-items-start flex-column justify-content-between">
                                        <div class="d-flex align-items-start w-100 justify-content-between">
                                            <h6>Enrolled Students:</h6>
                                            <span class="badge bg-primary w-25">{{ course.orders_count }}</span>
                                        </div>
                                        <div class="d-flex align-items-start w-100 justify-content-between">
                                            <h6>Zoom Class:</h6>
                                            <span class="badge bg-primary w-25">{{ zooms?.length }}</span>
                                        </div>
                                        <div class="d-flex align-items-start w-100 justify-content-between">
                                            <h6>Total Chapter:</h6>
                                            <span class="badge bg-primary w-25">{{ course?.chapters?.length }}</span>
                                        </div>
                                        <div class="d-flex align-items-start w-100 justify-content-between">
                                            <h6>Total Lesson:</h6>
                                            <span class="badge bg-primary w-25">{{ lessons?.length }}</span>
                                        </div>
                                        <div class="d-flex align-items-start w-100 justify-content-between">
                                            <h6>Total Mocktests:</h6>
                                            <span class="badge bg-primary w-25">{{ apiMocktests?.length }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex align-items-center gap-1">
                                        <h5 class="fw-bolder">Price: <span class="text-primary">{{
                                                course?.price
                                            }}</span> ৳</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body d-md-flex gap-3">
                            <div>
                                <h3 class="fw-bold text-primary">{{ course.name }}</h3>
                                <hr>
                                <small style="white-space: pre-wrap;">{{ course.description }}</small>
                                <div class="mt-3 d-flex align-items-start gap-1">
                                    <div class="d-flex align-items-start flex-column gap-1">
                                        <label>Current Status</label>
                                        <div class="d-flex gap-1">
                                            <span v-if="course.status === 'active'"
                                                  class="badge badge-light-success">{{ course.status }}</span>
                                            <span v-else class="badge badge-light-warning">{{ course.status }}</span>

                                            <button class="border-0 rounded-lg btn-primary"
                                                    @click="changeStatus(course.status === 'active' ? 'pending' : 'active')"
                                                    style="border-radius: 5px">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                                     viewBox="0 0 24 24">
                                                    <path fill="currentColor"
                                                          d="M4 20v-2h2.75l-.4-.35q-1.3-1.15-1.825-2.625T4 12.05q0-2.775 1.662-4.938T10 4.25v2.1Q8.2 7 7.1 8.563T6 12.05q0 1.125.425 2.188T7.75 16.2l.25.25V14h2v6zm10-.25v-2.1q1.8-.65 2.9-2.212T18 11.95q0-1.125-.425-2.187T16.25 7.8L16 7.55V10h-2V4h6v2h-2.75l.4.35q1.225 1.225 1.788 2.663T20 11.95q0 2.775-1.662 4.938T14 19.75"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!--                            <div class="d-flex align-items-center w-75">-->
                                <!--                                <div class="w-100 me-3">-->
                                <!--                                    <label>Change Status</label>-->
                                <!--                                    <select class="form-select me-3" @change="changeStatus($event)">-->
                                <!--                                        <option>~~ Select Status ~~</option>-->
                                <!--                                        <option value="active">Active</option>-->
                                <!--                                        <option value="pending">Pending</option>-->
                                <!--                                    </select>-->
                                <!--                                </div>-->
                                <!--                                <div>-->
                                <!--                                    <label>Current Status</label>-->
                                <!--                                    <span v-if="course.status == 'active'" class="badge badge-light-success">{{ course.status }}</span>-->
                                <!--                                    <span v-else class="badge badge-light-warning">{{ course.status }}</span>-->
                                <!--                                </div>-->
                                <!--                            </div>-->

                            </div>
                        </div>
                    </div>
                </div>
                <!--                <div class="col-12">
                                                    <div class="card mb-0">
                                                        <div class="card-body pb-0">
                                                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link active" id="home-tab"
                                                                            data-bs-toggle="tab" data-bs-target="#home"
                                                                            type="button" role="tab" aria-controls="home"
                                                                            aria-selected="true">Zoom Meetings</button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="profile-tab"
                                                                            data-bs-toggle="tab" data-bs-target="#profile"
                                                                            type="button" role="tab" aria-controls="profile"
                                                                            aria-selected="false">Mocktests</button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                                            data-bs-target="#contact" type="button" role="tab"
                                                                            aria-controls="contact" aria-selected="false">Lesson List</button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="video-tab" data-bs-toggle="tab"
                                                                            data-bs-target="#video" type="button" role="tab"
                                                                            aria-controls="video" aria-selected="false">Lesson Videos</button>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link" id="students-tab" data-bs-toggle="tab"
                                                                            data-bs-target="#students" type="button" role="tab"
                                                                            aria-controls="students" aria-selected="false">Enrolled Students</button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                            <div class="card mt-2 py-0">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                                            <h4>All Meetings</h4>
                                                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                    data-bs-target="#addZoom">Add New Meeting</button>
                                                                        </div>
                                                                        <table class="user-list-table table">
                                                                            <thead class="table-light">
                                                                            <tr class="">
                                                                                <th class="sorting">SL.</th>
                                                                                <th class="sorting">Title</th>
                                                                                <th class="sorting">Start Time</th>
                                                                                <th class="sorting">Action</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr v-for="(meeting, index) in course.zoomes" :key="meeting.id">
                                                                                <td>{{ meeting.meeting_title }}</td>
                                                                                <td>{{ format(meeting.start_time, 'll') }}</td>
                                                                                <td>
                                                                                    <a :href="meeting.join_url" target="_blank">Join</a>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="demo-inline-spacing">
                                                                                        <button type="button"
                                                                                                @click="deleteItem(`${props.main_url}/course/delete-meetings`, meeting.meeting_id)"
                                                                                                class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                                                                            <Icon title="trash"/>
                                                                                        </button>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                            <div class="card mt-2 py-0">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                                            <h4>All Mocktests</h4>
                                                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                    data-bs-target="#addMocktest">Add New Mocktest</button>
                                                                        </div>
                                                                        <table class="user-list-table table">
                                                                            <thead class="table-light">
                                                                            <tr class="">
                                                                                <th class="sorting">SL.</th>
                                                                                <th class="sorting">Title</th>
                                                                                <th class="sorting">Status</th>
                                                                                <th class="sorting">Action</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr v-for="(mocktest, index) in apiMocktests" :key="mocktest.id">
                                                                                <td>#{{ index+1 }}</td>
                                                                                <td>
                                                                                    <small>{{ mocktest.name }}</small>
                                                                                </td>
                                                                                <td>
                                                                                    <span v-if="mocktest.status == '1'" class="badge badge-light-primary">Active</span>
                                                                                    <span v-else class="badge badge-light-warning">Draft</span>
                                                                                </td>
                                                                                <th>
                                                                                    <button class="dropdown-item"
                                                                                            @click="deleteItem(`${props.mock_delete_url}/?mock_id=${mocktest.id}&course_id=${props.course.id}`,null)">
                                                                                        <Icon title="trash"/>

                                                                                        <span class="ms-1">Delete</span>
                                                                                    </button>
                                                                                </th>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                                            <div class="card mt-2 py-0">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                                            <h4>All Lessons List</h4>
                                                                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                    data-bs-target="#addNewChapter">Add New Lesson</button>
                                                                        </div>
                                                                        <table class="user-list-table table table-striped">
                                                                            <thead class="table-light">
                                                                            <tr class="">
                                                                                <th class="sorting">SL.</th>
                                                                                <th class="sorting">Title</th>
                                                                                <th class="sorting">Details</th>
                                                                                <th class="sorting">Videos Count</th>
                                                                                <th class="sorting">Status</th>
                                                                                <th class="sorting">Action</th>
                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <tr v-for="(chapter, i) in course.chapters" :key="chapter.id">
                                                                                <td>{{ i+1 }}</td>
                                                                                <td>{{ chapter.chapter_title }}</td>
                                                                                <td>{{ chapter.chapter_details }}</td>
                                                                                <td>{{ chapter.videos?.length }}</td>
                                                                                <td>{{ chapter.status }}</td>
                                                                                <td class="d-flex gap-1">
                                                                                    <button class="btn btn-sm btn-info">
                                                                            <span class="dropdown-item cursor-pointer"
                                                                                  @click="editChapterItem(props.chapter_url, chapter.id)">
                                                                                    <Icon title="pencil"/>
                                                                               <span class="ms-1">edit</span>
                                                                            </span>
                                                                                    </button>
                                                                                    <button class="btn btn-sm btn-danger">
                                                                            <span class="dropdown-item cursor-pointer"
                                                                                  @click="deleteItem(props.chapter_url, chapter.id)">
                                                                                    <Icon title="trash"/>
                                                                               <span class="ms-1">Delete</span>
                                                                            </span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                                                            <div class="row mt-2">
                                                                <div class="col-12 mx-auto">
                                                                    <div class="accordion" id="accordionExample">
                                                                        <div v-if="lessonVideos.length > 0" class="accordion-item mb-2">
                                                                            <h2 class="accordion-header" id="headingOne">
                                                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                                    <p> videos Without Chapter</p>
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                                <div class="accordion-body">
                                                                                    <strong>
                                                                                        All Videos Without Chapter ({{ lessonVideos.length }} videos)
                                                                                    </strong>
                                                                                    <div class="row mt-5">
                                                                                        <div class="col-md-11 col-12 ms-md-auto md-overflow-x-scroll">
                                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                                <h4>All Videos In This Lessons</h4>
                                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                                        data-bs-target="#createNewLesson">Add Video Or File</button>
                                                                                            </div>
                                                                                            <table class="user-list-table table table-responsive">
                                                                                                <thead class="table-light">
                                                                                                <tr class="">
                                                                                                    <th class="sorting">SL.</th>
                                                                                                    <th class="sorting">Title</th>
                                                                                                    <th class="sorting">Status</th>
                                                                                                    <th class="sorting">Actions</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <tr v-for="(lesson, index) in lessonVideos" :key="lesson?.id">
                                                                                                    <td>#{{ index+1 }}</td>
                                                                                                    <td>
                                                                                                        <small>{{ lesson?.name }}</small>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <span v-if="lesson?.status == '1'" class="badge badge-light-primary">Active</span>
                                                                                                        <span v-else class="badge badge-light-warning">Draft</span>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <div class="btn-group dropdown dropdown-icon-wrapper">
                                                                                                            <button type="button"
                                                                                                                    class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                                                            </button>

                                                                                                            <div class="dropdown-menu">
                                                                                                            <span class="dropdown-item"
                                                                                                                  v-if="lesson.status == 1"
                                                                                                                  @click="avticationStatus(lesson?.id, false)">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                                                                                                <span class="ms-1">Inactive</span>
                                                                                                            </span>
                                                                                                                <span class="dropdown-item"
                                                                                                                      v-else @click="avticationStatus(lesson?.id, true)">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                                                                                                <span class="ms-1">Active</span>
                                                                                                            </span>

                                                                                                                <span v-if="lesson.content != null" class="dropdown-item"
                                                                                                                      @click="showFile(lesson?.content, lesson.name)">
                                                                                                                        <Icon title="eye"/>
                                                                                                                       <span class="ms-1">File</span>
                                                                                                            </span>

                                                                                                                <span v-if="lesson.file != null" class="dropdown-item"
                                                                                                                      @click="showPDF(lesson?.file)">
                                                                                                                <Icon title="eye"/>
                                                                                                                <span class="ms-1">File</span>
                                                                                                            </span>

                                                                                                                <span v-if="lesson.video" class="dropdown-item"
                                                                                                                      @click="playVideo(lesson?.video, lesson.name)">
                                                                                                                <Icon title="play-circle"/>
                                                                                                               <span class="ms-1">Show</span>
                                                                                                            </span>

                                                                                                                <span class="dropdown-item"
                                                                                                                      @click="editCategory(lesson?.id)">
                                                                                                                <Icon title="pencil"/>
                                                                                                                <span class="ms-1">Edit</span>
                                                                                                            </span>

                                                                                                                <span class="dropdown-item"
                                                                                                                      @click="deleteItem(props.lesson_index, lesson?.id)">
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
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="accordion-item mb-2" v-for="(chap,i) in course.chapters">
                                                                            <h2 class="accordion-header" id="headingOnes">
                                                                                <button class="accordion-button text-"
                                                                                        type="button"
                                                                                        data-bs-toggle="collapse"
                                                                                        :data-bs-target="`#collups${i}`"
                                                                                        aria-expanded="true"
                                                                                        :aria-controls="`collups${i}`">
                                                                                    {{ chap?.chapter_title }}
                                                                                </button>
                                                                            </h2>
                                                                            <div :id="`collups${i}`"
                                                                                 class="accordion-collapse collapse"
                                                                                 :aria-labelledby="`collups${i}`"
                                                                                 data-bs-parent="#accordionExample">
                                                                                <div class="accordion-body ">
                                                                                    <strong class="">
                                                                                        {{ chap?.chapter_title }} - {{ chap?.videos?.length }} Videos
                                                                                    </strong>
                                                                                    <p>{{ chap?.chapter_details }}</p>

                                                                                    <div class="row mt-5">
                                                                                        <div class="col-md-11 col-12 ms-md-auto md-overflow-x-scroll">
                                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                                <h4>All Videos In This Lessons</h4>
                                                                                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                                                                                                        data-bs-target="#createNewLesson">Add Video Or File</button>
                                                                                            </div>
                                                                                            <table class="user-list-table table table-responsive">
                                                                                                <thead class="table-light">
                                                                                                <tr class="">
                                                                                                    <th class="sorting">SL.</th>
                                                                                                    <th class="sorting">Title</th>
                                                                                                    <th class="sorting">Status</th>
                                                                                                    <th class="sorting">Actions</th>
                                                                                                </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                <tr v-for="(lesson, index) in chap.videos" :key="lesson?.id">
                                                                                                    <td>#{{ index+1 }}</td>
                                                                                                    <td>
                                                                                                        <small>{{ lesson?.name }}</small>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <span v-if="lesson?.status == '1'" class="badge badge-light-primary">Active</span>
                                                                                                        <span v-else class="badge badge-light-warning">Draft</span>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <div class="btn-group dropdown dropdown-icon-wrapper">
                                                                                                            <button type="button"
                                                                                                                    class="btn dropdown-toggle dropdown-toggle-split waves-effect waves-float waves-light"
                                                                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                                                                                                            </button>

                                                                                                            <div class="dropdown-menu">
                                                                                                            <span class="dropdown-item"
                                                                                                                  v-if="lesson.status == 1"
                                                                                                                  @click="avticationStatus(lesson?.id, false)">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down-left"><line x1="17" y1="7" x2="7" y2="17"></line><polyline points="17 17 7 17 7 7"></polyline></svg>
                                                                                                                <span class="ms-1">Inactive</span>
                                                                                                            </span>
                                                                                                                <span class="dropdown-item"
                                                                                                                      v-else @click="avticationStatus(lesson?.id, true)">
                                                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-right"><line x1="7" y1="17" x2="17" y2="7"></line><polyline points="7 7 17 7 17 17"></polyline></svg>
                                                                                                                <span class="ms-1">Active</span>
                                                                                                            </span>

                                                                                                                <span v-if="lesson.content != null" class="dropdown-item"
                                                                                                                      @click="showFile(lesson?.content, lesson.name)">
                                                                                                                        <Icon title="eye"/>
                                                                                                                       <span class="ms-1">File</span>
                                                                                                            </span>

                                                                                                                <span v-if="lesson.file != null" class="dropdown-item"
                                                                                                                      @click="showPDF(lesson?.file)">
                                                                                                                <Icon title="eye"/>
                                                                                                                <span class="ms-1">File</span>
                                                                                                            </span>

                                                                                                                <span v-if="lesson.video" class="dropdown-item"
                                                                                                                      @click="playVideo(lesson?.video, lesson.name)">
                                                                                                                <Icon title="play-circle"/>
                                                                                                               <span class="ms-1">Show</span>
                                                                                                            </span>

                                                                                                                <span class="dropdown-item"
                                                                                                                      @click="editCategory(lesson?.id)">
                                                                                                                <Icon title="pencil"/>
                                                                                                                <span class="ms-1">Edit</span>
                                                                                                            </span>

                                                                                                                <span class="dropdown-item"
                                                                                                                      @click="deleteItem(props.lesson_index, lesson?.id)">
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
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tab-pane fade" id="students" role="tabpanel" aria-labelledby="students-tab">
                                                            <div class="card mt-2 py-0">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                                    <h4>Enrolled Students</h4>
                                                                                </div>

                                                                                <table class="user-list-table table">
                                                                                    <thead class="table-light">
                                                                                    <tr class="">
                                                                                        <th class="sorting">Name</th>
                                                                                        <th class="sorting">Phone</th>
                                                                                        <th class="sorting">Active on</th>
                                                                                        <th class="sorting">Active Status</th>
                                                                                        <th class="sorting">Actions</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    <tr v-for="student in course.orders" :key="student.id">
                                                                                        <td>
                                                                                            <div class="d-flex justify-content-left align-items-center">
                                                                                                <div class="avatar-wrapper">
                                                                                                    <div class="avatar  me-1">
                                                                                                        <img :src="`https://ui-avatars.com/api/?background=random&color=fff&name=${student?.user?.name}`"
                                                                                                             :alt="student.user?.name" height="32" width="32">
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="d-flex flex-column">
                                                                                                    <div class="student_name text-truncate text-body">
                                                                                                        <span class="fw-bolder">{{ student.user?.name }}</span>
                                                                                                    </div>
                                                                                                    <small class="emp_post text-muted">{{ student.user?.email }}</small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>{{ student.user?.phone }}</td>
                                                                                        <td>{{ student.user?.active_on }}</td>
                                                                                        <td>
                                                                                            <span class="badge bg-light-primary" v-if="student.user?.is_active">Activated</span>
                                                                                            <span class="badge bg-light-warning" v-else>Deactivated</span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="btn-group dropup dropdown-icon-wrapper">
                                                                                                <a class="dropdown-item" :href="`/students/${student.user?.id}`" target="_blank">
                                                                                                    <Icon title="eye"/>
                                                                                                    <span class="ms-1">Show</span>
                                                                                                </a>
                                                                                            </div>

                                                                                            &lt;!&ndash;
                                                                                                                        <div class="demo-inline-spacing">

                                                                                                                            <button type="button"
                                                                                                                                    @click="editItem(student.show_url)"
                                                                                                                                    class="btn btn-icon btn-icon rounded-circle bg-light-primary waves-effect waves-float waves-light">
                                                                                                                                <Icon title="pencil" />
                                                                                                                            </button>
                                                                                                                            <button type="button"
                                                                                                                                    @click="showItem(student.show_url)"
                                                                                                                                    class="btn btn-icon btn-icon rounded-circle bg-light-warning waves-effect waves-float waves-light">
                                                                                                                                <Icon title="eye" />
                                                                                                                            </button>
                                                                                                                            <button @click="deleteItemModal(student.delete_url)" type="button"
                                                                                                                                    class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                                                                                                                <Icon title="trash"/>
                                                                                                                            </button>
                                                                                                                            <button @click="deactiveStudent(student.deactivate_student)" type="button"
                                                                                                                                    class="btn btn-icon btn-icon rounded-circle waves-effect waves-float waves-light bg-light-danger">
                                                                                                                                <Icon title="trash"/>
                                                                                                                            </button>
                                                                                                                        </div>&ndash;&gt;
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
            </div>

            <div class="row">
                <Meetings :liveClass="liveClass"
                          :courseId="course?.id"
                          :errors="errors"/>
                <Mocktests :apiMocktests="apiMocktests"
                           :course="course"
                           :mocktests="mocktests"
                           :saveMocktest="save_mocktest"
                           :errors="errors"
                           :deleteMockUrl="mock_delete_url"
                />
                <LessonVideos :course="course"
                              :chatperUrl="props.chapter_url"
                              :lessonIndex="props.lesson_index"
                              :saveLessonUrl="props.lesson_store_url"
                              :errors="props.errors"
                />
            </div>
        </div>

        <Modal id="showVideo" size="lg" title="Show Course Video" v-vb-is:modal>
            <div class="modal-body">
                <div class="w-100 h-100" id="attatchedVideo"></div>
            </div>
        </Modal>
        <!--    made by me this pdf viewer     -->
        <Modal id="showFile" size="lg" title="Show Course Video" v-vb-is:modal>
            <div class="modal-body" id="showContent">
            </div>
        </Modal>
        <!--    made by me this pdf viewer     -->
        <!-- cloud pdf viewer io pdf viewer components     -->
        <Modal id="showPDFfile" size="lg" title="Show Course Video" v-vb-is:modal>
            <div class="modal-body">
                <div id="webviewer" ref="viewer"></div>
            </div>
        </Modal>
        <!-- cloud pdf viewer io pdf viewer components     -->


        <!--    update lessions-->
        <Modal id="updateLesson" title="Update Lesson" v-vb-is:modal size="lg">
            <form @submit.prevent="updateSingleLesson(updateLesson.id)">
                <div class="modal-body">
                    <Text v-model="updateLesson.name" type="text" label="Lesson Title" :error="updateLesson.errors.name"
                          placeholder="Lesson title"/>
                    <span class="text-danger text-small" v-if="updateLesson.course_id">{{ errors.mocktest_id }}</span>

                    <Text v-model="updateLesson.video" label="Lesson Video- (embed-url)"
                          placeholder="Lesson Video Url"/>
                    <Text type="text" v-model="updateLesson.file" label="Chose Related File"
                          placeholder="Add Related Files"/>
                    <Textarea v-model="updateLesson.description" label="Lesson Description"
                              :error="createForm.errors.description" placeholder="Lesson description"/>

                    <label>Chapter Id</label>
                    <div>
                        <v-select v-model="updateLesson.chapter_id"
                                  label="chapter_title"
                                  placeholder="Select Lesson For Assign Video"
                                  :options="chapers"
                                  :reduce="ch => ch.id"
                        ></v-select>
                        <span class="text-danger" v-if="createForm.errors.chapter_id">{{
                                createForm.errors.chapter_id
                            }}</span>

                    </div>
                    &lt;!&ndash; <label class="form-label">Course content</label>&ndash;&gt;
                    &lt;!&ndash;
                    <TextEditor v-model="updateLesson.content"/>&ndash;&gt;
                    <label class="form-check-label mb-50" for="lessonStatus">Lesson Status: </label>
                    <div class="form-check form-switch form-check-success">
                        <input type="checkbox" class="form-check-input" id="lessonStatus" v-model="updateLesson.status"
                               :true-value="true" :false-value="false"/>
                        <label class="form-check-label" for="lessonStatus">
                        <span class="switch-icon-left">
                            <Icon title="check"/>
                        </span>
                            <span class="switch-icon-right">
                            <Icon title="x"/>
                        </span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button :disabled="createForm.processing" type="submit"
                            class="btn btn-primary waves-effect waves-float waves-light">Submit
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">Cancel
                    </button>
                </div>
            </form>
        </Modal>

    </Layout>
</template>


<style scoped>
table td,
th {
    padding: 0.5rem;
}

#webviewer {
    height: 400px;
    width: 100%;
}

.jss339 {
    display: none !important;
}

.course-list-group {
    list-style: none;
}

.course-list-group li:last-child {
    border-bottom: none !important;
}

.last-video-border:last-child {
    border-bottom: none !important;
}

.hover-button-style:hover .action-btn-style {
    display: flex !important;
}

</style>
