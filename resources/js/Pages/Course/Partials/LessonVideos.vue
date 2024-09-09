<template>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="w-100 d-flex justify-content-between align-items-center pb-2">
                    <p class="fw-bolder p-0 fs-5">Lesson Videos</p>

                    <button class="btn btn-icon btn-circle btn-sm btn-primary" @click="openAddChaper">
                        <vue-feather type="plus" size="15"/>
                    </button>
                </div>
                <div class="border-top py-2 content-scrollbar">
                    <ul class="ps-0 course-list-group d-flex flex-column gap-2">
                        <li class="pb-2 course-list-item" v-for="chapter in  props.course.chapters">

                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <div class="w-100">
                                    <div class="bg-light-primary sticky-top hover-button-style rounded p-1 d-flex justify-content-between align-items-center">
                                        <p class="text-primary fw-bolder m-0 p-0 fs-5 text-capitalize">{{ chapter?.chapter_title }}</p>

                                        <div class="d-none gap-1 action-btn-style">
                                            <span class="cursor-pointer" @click="editChapter(chapter)">
                                                <vue-feather type="edit-3" size="15"/>
                                            </span>
                                            <span class="cursor-pointer" @click="deleteItem(props.chatperUrl, chapter?.id)">
                                                <vue-feather type="trash" size="15"/>
                                            </span>
                                        </div>
                                    </div>

                                    <button class="my-1 d-flex gap-1 align-items-center justify-content-center btn w-100 btn-icon btn-circle btn-sm bg-light-primary"
                                            type="button" @click="addLesson(chapter.id)">
                                        <vue-feather type="plus" size="15"/>
                                        <span>Add New Content</span>
                                    </button>
                                    <div class="w-100 d-flex align-items-start flex-column">
                                        <div
                                            class="border-bottom last-video-border w-100 d-flex align-items-start flex-column justify-content-between"
                                            v-for="item in chapter.videos">
                                            <div
                                                class="w-100 py-1 d-flex align-items-start justify-content-between">
                                                <div>
                                                    <div class="d-flex gap-2">
                                                        <p class="fw-bold text-black text-capitalize m-0 p-0">{{ item?.name }}</p>
                                                        <span :class="item.status ? 'badge badge-light-success' : 'badge badge-light-warning'">{{ item.status ? 'Active' : 'Pending' }}</span>
                                                    </div>
                                                    <small>{{ item?.description?.slice(0, 50) }}</small>
                                                </div>
                                                <div class="btn-group dropup dropdown-icon-wrapper">
                                                    <button type="button"
                                                            class="btn btn-sm dropdown-toggle border-0 dropdown-toggle-split waves-effect waves-float waves-light"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <vue-feather type="more-vertical" size="15"/>
                                                    </button>
                                                    <div class="dropdown-menu py-0">
                                                        <span class="dropdown-item d-flex gap-1 align-items-center" @click="editLesson(item)">
                                                            <vue-feather type="edit-3" size="13"/>
                                                            <span>Edit</span>
                                                        </span>
                                                        <span @click="deleteItem(props.lessonIndex, item?.id)" class="dropdown-item d-flex gap-1 align-items-center">
                                                            <vue-feather type="trash-2" size="13"/>
                                                            <span>Delete</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



    <Modal id="addNewChapter" title="Add New Lesson" v-vb-is:modal>
        <form @submit.prevent="addNewChapter">
            <div class="modal-body">
                <Text v-model="lessonForm.title" label="Lesson Title" placeholder="e.g Lesson Name"/>
                <Textarea v-model="lessonForm.desc" label="Lesson Details"
                          placeholder="e.g Describe About This Lessons"/>
                <select v-model="lessonForm.status" class="form-control"
                        placeholder="e.g Describe About This Lessons">
                    <option value="null" selected disabled>Select Status</option>
                    <option value="approved">Approve</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div class="modal-footer">
                <button :disabled="processing" type="submit"
                        class="btn btn-primary waves-effect waves-float waves-light">Submit
                </button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel
                </button>
            </div>
        </form>
    </Modal>


    <Modal id="createNewLesson" title="Create New Lesson" v-vb-is:modal size="lg">
        <form @submit.prevent="createNewLesson">
            <div class="modal-body">
                <Text v-model="createForm.name" type="text" label="Lesson Title" :error="createForm.errors.name"
                      placeholder="Lesson title"/>
                <Text v-model="createForm.video" label="Lesson Video- (embed-url)" placeholder="Lesson Video Url"/>
                <Text type="text" v-model="createForm.file" label="Chose Related File"
                      placeholder="Add Related Files"/>
                <Textarea v-model="createForm.description" label="Lesson Description"
                          :error="createForm.errors.description" placeholder="Lesson description"/>

                <label>Chapter Id</label>
<!--                <div>
                    <v-select v-model="createForm.chapter_id"
                              label="chapter_title"
                              placeholder="Select Lesson For Assign Video"
                              :options="chapers"
                              :reduce="ch => ch.id"
                    ></v-select>
                    <span class="text-danger" v-if="createForm.errors.chapter_id">{{
                            createForm.errors.chapter_id
                        }}</span>

                </div>-->
                <label class="form-check-label mb-50 mt-3" for="customSwitch111">Lesson Status: </label>
                <div class="form-check form-switch form-check-success">
                    <input type="checkbox" class="form-check-input" id="customSwitch111"
                           v-model="createForm.status"
                           :true-value="true"
                           :false-value="false"/>
                    <label class="form-check-label" for="customSwitch111">
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

</template>


<script setup>
import Text from "@/Components/form/Text.vue";
import Textarea from "@/Components/form/Textarea.vue";
import Modal from "@/Components/Modal.vue";
import { useForm } from "@inertiajs/vue3";
import Icon from "@/Components/Icon.vue";
import {useAction} from "@/Composable/useAction.js";
import {ref} from "vue";
const {deleteItem} = useAction();

const props = defineProps({
    course: Object,
    chatperUrl: String,
    saveLessonUrl: String,
    lessonIndex: String,
    errors: Object,
})


let processing = ref(false)

const lessonForm = useForm({
    course_id: props.course.id,
    title: null,
    desc: null,
    status: null,
})

const openAddChaper = () => {
    lessonForm.id = null
    lessonForm.title = null
    lessonForm.desc = null
    lessonForm.status =  null
    document.getElementById('addNewChapter').$vb.modal.show()
}
const editChapter = (chatper) => {
    console.log(chatper)
    lessonForm.id = chatper.id
    lessonForm.course_id = chatper.course_id
    lessonForm.title = chatper.chapter_title
    lessonForm.desc = chatper.chapter_details
    lessonForm.status = chatper.status

    document.getElementById('addNewChapter').$vb.modal.show()
}
let addNewChapter = () => {
    if(lessonForm.id){
        lessonForm.put(props.chatperUrl + "/" + lessonForm.id, {
            onSuccess: () => {
                document.getElementById('addNewChapter').$vb.modal.hide()
                lessonForm.reset()
            }
        })
    }else{
        lessonForm.post(props.chatperUrl, {
            onSuccess: () => {
                document.getElementById('addNewChapter').$vb.modal.hide()
                lessonForm.reset()
            }
        });
    }
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

const addLesson = (lessonId) =>{
    createForm.chapter_id = lessonId
    createForm.id = null
    createForm.chapter_id = null
    createForm.description = null
    createForm.video = null
    createForm.status = false
    createForm.file = null
    createForm.content = null
    document.getElementById('createNewLesson').$vb.modal.show()
}
const editLesson = (lesson) =>{
    console.log(lesson)
    createForm.id = lesson.id;
    createForm.chapter_id = lesson.chapter_id;
    createForm.name = lesson.name;
    createForm.description = lesson.description;
    createForm.video = lesson.video;
    createForm.status = lesson.status === 1;
    createForm.file = lesson.file;
    createForm.content = lesson.content;

    document.getElementById('createNewLesson').$vb.modal.show()
}


let createNewLesson = () => {
    if(createForm?.id){
        createForm.post(props.lessonIndex + "/" + createForm.id + "/update", {
            onSuccess: () => {
                document.getElementById('createNewLesson').$vb.modal.hide()
                createForm.reset()
            }
        })
    }else{
        createForm.post(props.saveLessonUrl, {
            onSuccess: () => {
                document.getElementById('createNewLesson').$vb.modal.hide()
                createForm.reset()
            }
        });
    }
}

</script>

<style>

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

.content-scrollbar{
    max-height:800px;
    overflow-y: scroll;
}
.content-scrollbar::-webkit-scrollbar{
    display: none;
}
</style>
