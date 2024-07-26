<template>

    <Layout>
        <Head title="Exam Result List"/>
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row">
                    <div class="col-12">
                        <h2 class="content-header-title mb-0">Student Exam Result</h2>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-body d-flex justify-content-between w-100">
                        <div class="w-50">
                            <h3 class="fw-bold">Exam Title: {{ props?.result?.mocktest?.name }}</h3>
                            <p>Duration: {{ props?.result?.mocktest?.duration}}</p>
                            <p>Total Questions: {{ props?.result?.total_q }}</p>
                            <p>Achived Mark: {{ props?.result?.mark }}</p>
                            <p>Position: {{ props?.result?.position }}Out Of {{ props?.result?.partisipants }}</p>
                            <p>Total Correct: {{ props.result?.total_correct }}</p>
                            <p>Total Incorrect: {{ props.result?.total_incaorrect }}</p>
                            <p>Given Exam Time: {{ moment(props.result?.created_at).format('lll') }}</p>
                        </div>
                        <div class="w-50  d-flex gap-2 align-items-start" style="border-left: 1px solid gray">
                            <img class="ms-5" style="width:75px" :src="`https://ui-avatars.com/api/?background=random&name=${props?.result?.user?.name}&rounded=true&size=50`" alt="">

                            <div class="ms-3">
                                <h3>Student Name: {{ props?.result?.user?.name }}</h3>
                                <p>Email: {{ props?.result?.user?.email }}</p>
                                <p>Phone: {{ props?.result?.user?.phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-10 mx-auto mt-8">
<!--                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab"
                                data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home"
                                aria-selected="true">All Solutions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab"
                                data-bs-toggle="tab" data-bs-target="#profile"
                                type="button" role="tab" aria-controls="profile"
                                aria-selected="false">Correct Answers</button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="wrong-tab"
                                data-bs-toggle="tab" data-bs-target="#wrong"
                                type="button" role="tab" aria-controls="wrong"
                                aria-selected="false">Wrong Answers</button>
                    </li>
                </ul>-->
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h1 class="mt-5">All Answer Solutions</h1>

                        <div class="card mt-4" v-for="(ans, i) in  props?.result?.given_answers" :key="`ans-${ans.id}`">
                            <div class="card-body q__separator">
                                <h3>Question - {{ i + 1 }} :</h3>
                                <h6 class="examQuestion text-capitalize" v-html="ans?.question?.title"/>
                                <hr>
                                <div>
                                    <ul class="border-0 m-0 p-0 d-flex flex-column gap-1">
                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'a') || (ans.question.answer === 'a'),
                                                'bg-light-danger': ans.given_ans === 'a' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.a"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'a') || (ans.question.answer === 'a')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'a'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>

                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'b') || (ans.question.answer === 'b'),
                                                'bg-light-danger': ans.given_ans === 'b' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.b"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'b') || (ans.question.answer === 'b')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'b'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>



                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'c') || (ans.question.answer === 'c'),
                                                'bg-light-danger': ans.given_ans === 'c' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.c"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'c') || (ans.question.answer === 'c')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'c'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>



                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'd') || (ans.question.answer === 'd'),
                                                'bg-light-danger': ans.given_ans === 'd' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.d"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'd') || (ans.question.answer === 'd')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'd'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>


                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'e') || (ans.question.answer === 'e'),
                                                'bg-light-danger': ans.given_ans === 'e' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.e"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'e') || (ans.question.answer === 'e')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'e'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="feedback">
                                    <h6 class="text-start">Question Feedback: </h6>
                                    <div v-html="ans.question?.feedback" class="shadow-sm p-3 rounded-sm"/>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <h1 class="mt-5">Correct Answer</h1>
                        <div class="card mt-4" v-for="(ans, i) in correctAns" :key="`ans-${ans.id}`">
                            <div class="card-body q__separator">
                                <h3>Question - {{ i + 1 }} :</h3>
                                <h6 class="examQuestion text-capitalize" v-html="ans?.question?.title"/>
                                <hr>
                                <div>
                                    <ul class="border-0 m-0 p-0 d-flex flex-column gap-1">
                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'a') || (ans.question.answer === 'a'),
                                                'bg-light-danger': ans.given_ans === 'a' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.a"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'a') || (ans.question.answer === 'a')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'a'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>

                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'b') || (ans.question.answer === 'b'),
                                                'bg-light-danger': ans.given_ans === 'b' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.b"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'b') || (ans.question.answer === 'b')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'b'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>



                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'c') || (ans.question.answer === 'c'),
                                                'bg-light-danger': ans.given_ans === 'c' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.c"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'c') || (ans.question.answer === 'c')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'c'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>



                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'd') || (ans.question.answer === 'd'),
                                                'bg-light-danger': ans.given_ans === 'd' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.d"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'd') || (ans.question.answer === 'd')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'd'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>


                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'e') || (ans.question.answer === 'e'),
                                                'bg-light-danger': ans.given_ans === 'e' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.e"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'e') || (ans.question.answer === 'e')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'e'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="feedback">
                                    <h6 class="text-start">Question Feedback: </h6>
                                    <div v-html="ans.question?.feedback" class="shadow-sm p-3 rounded-md"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="wrong" role="tabpanel" aria-labelledby="wrong-tab">
                        <h1 class="mt-5">Wrong Answer</h1>
                        <div class="card mt-4" v-for="ans in incorrectAns" :key="`ans-${ans.id}`">
                            <div class="card-body q__separator">
                                <h6 class="examQuestion" v-html="ans?.question?.title"/>
                                <hr>
                                <div>
                                    <ul class="border-0 m-0 p-0 d-flex flex-column gap-1">
                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'a') || (ans.question.answer === 'a'),
                                                'bg-light-danger': ans.given_ans === 'a' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.a"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'a') || (ans.question.answer === 'a')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'a'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>

                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'b') || (ans.question.answer === 'b'),
                                                'bg-light-danger': ans.given_ans === 'b' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.b"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'b') || (ans.question.answer === 'b')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'b'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>



                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'c') || (ans.question.answer === 'c'),
                                                'bg-light-danger': ans.given_ans === 'c' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.c"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'c') || (ans.question.answer === 'c')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'c'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>



                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'd') || (ans.question.answer === 'd'),
                                                'bg-light-danger': ans.given_ans === 'd' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.d"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'd') || (ans.question.answer === 'd')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'd'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>


                                        <li class="d-flex align-items-center gap-4 p-2 shadow-sm border-0 justify-content-between rounded-md"
                                            :class="{
                                                'bg-light-success': (ans?.given_ans === ans.question.answer && ans.given_ans === 'e') || (ans.question.answer === 'e'),
                                                'bg-light-danger': ans.given_ans === 'e' && ans?.given_ans !== ans.question.answer
                                            }">
                                            <p v-html="ans.question.e"></p>
                                            <span v-if="(ans?.given_ans === ans.question.answer && ans.given_ans === 'e') || (ans.question.answer === 'e')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                            </span>
                                            <span  v-else-if="ans.given_ans === 'e'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                                                </svg>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="feedback">
                                    <h6 class="text-start">Question Feedback: </h6>
                                    <div v-html="ans.question?.feedback" class="shadow-sm p-3 rounded-md"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>


    </Layout>

</template>

<script setup>
import Layout from "@/Shared/Layout.vue";
import moment from "moment";
import {computed} from 'vue'

let props = defineProps({
    result: Object,
});

</script>
<style>
p{
    margin: 0 !important;
    padding: 0!important;
}
</style>
