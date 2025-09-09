<template>
    <div>
        <!-- Topic Filter Dropdown -->
        <!-- <div class="mb-3">
            <label for="topicFilter" class="form-label font-weight-bold">Filter by Topic</label>
            <select id="topicFilter" v-model="selectedTopic" @change="onTopicChange" class="form-control">
                <option value="">All Topics</option>
                <option v-for="topic in topics" :key="topic.id" :value="topic.id">{{ topic.title }}</option>
            </select>
        </div> -->
        <!-- Display Filtered Questions -->
        <!-- <div v-if="filteredQuestions.data && filteredQuestions.data.length">
            <h5>Questions ({{ filteredQuestions.data.length }} found)</h5>
            <p>Selected: {{ selectedQuestions.length }} questions</p>
        </div> -->
        <form @submit.prevent="submitHandler">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="text-capitalize">
                        {{ param_id ? `${setup.edit_page_title}` : `${setup.create_page_title}` }}

                    </h5>
                    <div>
                        <router-link v-if="item.slug" class="btn btn-outline-info mr-2 btn-sm" :to="{
                            name: `Details${setup.route_prefix}`,
                            params: { id: item.slug },
                        }">
                            {{ setup.details_page_title }}
                        </router-link>
                        <router-link class="btn btn-outline-warning btn-sm" :to="{ name: `All${setup.route_prefix}` }">
                            {{ setup.all_page_title }}
                        </router-link>
                    </div>
                </div>
                <div class="card-body card_body_fixed_height">
                    <div class="row">

                        <template v-for="(form_field, index) in form_fields" v-bind:key="index">

                            <common-input :label="form_field.label" :type="form_field.type" :name="form_field.name"
                                :multiple="form_field.multiple" :value="form_field.value"
                                :data_list="form_field.data_list" :is_visible="form_field.is_visible"
                                :row_col_class="form_field.row_col_class" />

                        </template>

                        <!-- Topic Filter Dropdown -->
                        <div class="col-12 mt-4">
                            <div class="mb-3">
                                <label for="topicFilter" class="form-label font-weight-bold">Filter by Topic</label>
                                <select id="topicFilter" v-model="selectedTopic" @change="onTopicChange"
                                    class="form-control">
                                    <option value="">All Topics</option>
                                    <option v-for="topic in topics" :key="topic.id" :value="topic.id">{{ topic.title }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <h5>Quiz Questions
                                <span v-if="filteredQuestions.data" class="badge badge-info">
                                    {{ filteredQuestions.data.length }} available
                                </span>
                                <span v-if="selectedQuestions.length > 0" class="badge badge-success ml-2">
                                    {{ selectedQuestions.length }} selected
                                </span>
                            </h5>
                            <div>
                                <button type="button" class="btn btn-sm btn-primary mb-2" @click="selectAllQuestions">
                                    Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary mb-2 ml-2"
                                    @click="deselectAllQuestions">
                                    Deselect All
                                </button>
                                <ul style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                    <li v-for="question in filteredQuestions.data ? filteredQuestions.data : []"
                                        :key="question.id" :style="{
                                            listStyle: 'none',
                                            marginBottom: '8px',
                                            padding: '5px',
                                            borderBottom: '1px solid #eee',
                                            backgroundColor: selectedTopic && question.quiz_question_topic_id.id != selectedTopic ? '#111' : 'transparent',
                                            border: selectedTopic && question.quiz_question_topic_id.id != selectedTopic ? '1px solid #dee2e6' : 'none',
                                            borderRadius: selectedTopic && question.quiz_question_topic_id.id != selectedTopic ? '4px' : '0'
                                        }">
                                        <input type="checkbox" :value="question.id" v-model="selectedQuestions"
                                            :id="'question-' + question.id" class="mr-2" />
                                        <label :for="'question-' + question.id" style="margin: 0; cursor: pointer;">
                                            <strong>{{ question.title }}</strong>
                                            <span
                                                v-if="selectedTopic && question.quiz_question_topic_id.id != selectedTopic"
                                                class="badge badge-info ml-2" style="font-size: 10px;">
                                                From other topic
                                            </span>
                                            <br>
                                            <small class="text-muted">
                                                Topic: {{ question.quiz_question_topic_id ?
                                                    question.quiz_question_topic_id.title : 'N/A' }} |
                                                Level: {{ question.question_level || 'N/A' }} |
                                                Mark: {{ question.mark || 0 }}
                                            </small>
                                        </label>
                                    </li>
                                    <li v-if="!filteredQuestions.data || filteredQuestions.data.length === 0"
                                        class="text-center text-muted">
                                        No questions found
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-light btn-square px-5">
                        <i class="icon-lock"></i>
                        Submit
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { mapActions, mapState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import form_fields from "../setup/form_fields";

export default {
    components: {},
    data: () => ({
        setup,
        form_fields,
        param_id: null,
        topics: [], // List of topics
        selectedTopic: '', // Selected topic for filtering
        filteredQuestions: [], // Filtered questions
        selectedQuestions: [], // Array to store selected question IDs
    }),
    created: async function () {
        let id = (this.param_id = this.$route.params.id);
        this.reset_fields();
        await this.fetchTopics();
        // fetchQuestions is now called inside fetchTopics via extractQuestionsFromTopics
        if (id) {
            this.set_fields(id);
        }
    },
    mounted() {
        // Prevent non-numeric input for negative_value
        this.$nextTick(() => {
            const input = document.querySelector('input[name="negative_value"]');
            if (input) {
                input.addEventListener('input', function (e) {
                    // Remove any non-digit characters (allow negative sign and decimal)
                    let val = input.value;
                    val = val.replace(/[^0-9.-]/g, '');
                    // Only allow one negative sign at the start
                    val = val.replace(/(?!^)-/g, '');
                    // Only allow one decimal point
                    val = val.replace(/(\..*)\./g, '$1');
                    input.value = val;
                });
            }
        });
    },
    methods: {
        ...mapActions(store, {
            create: "create",
            update: "update",
            details: "details",
            get_all: "get_all",
            set_only_latest_data: "set_only_latest_data",
        }),
        async fetchTopics() {
            // Fetch topics from API
            const res = await fetch('api/v1/quiz-question-topics');
            if (res.ok) {
                const result = await res.json();
                this.topics = result.data.data;
                // Extract all questions from all topics
                this.extractQuestionsFromTopics();
            }
        },
        extractQuestionsFromTopics() {
            // Extract all questions from topics and create filteredQuestions structure
            let allQuestions = [];
            this.topics.forEach(topic => {
                if (topic.quiz_question && Array.isArray(topic.quiz_question)) {
                    topic.quiz_question.forEach(question => {
                        // Add topic info to question for display
                        question.quiz_question_topic_id = {
                            id: topic.id,
                            title: topic.title
                        };
                        allQuestions.push(question);
                    });
                }
            });

            // Create pagination-like structure to match existing code
            this.filteredQuestions = {
                data: allQuestions,
                total: allQuestions.length
            };
        },
        async fetchQuestions() {
            // Filter questions based on selected topic
            let filteredQuestions = [];

            if (!this.selectedTopic) {
                // Show all questions
                this.topics.forEach(topic => {
                    if (topic.quiz_question && Array.isArray(topic.quiz_question)) {
                        topic.quiz_question.forEach(question => {
                            question.quiz_question_topic_id = {
                                id: topic.id,
                                title: topic.title
                            };
                            filteredQuestions.push(question);
                        });
                    }
                });
            } else {
                // Filter questions by selected topic, but also include selected questions from other topics
                const selectedTopicData = this.topics.find(t => t.id == this.selectedTopic);
                if (selectedTopicData && selectedTopicData.quiz_question) {
                    selectedTopicData.quiz_question.forEach(question => {
                        question.quiz_question_topic_id = {
                            id: selectedTopicData.id,
                            title: selectedTopicData.title
                        };
                        filteredQuestions.push(question);
                    });
                }

                // Add selected questions from other topics to maintain visibility
                this.topics.forEach(topic => {
                    if (topic.id != this.selectedTopic && topic.quiz_question && Array.isArray(topic.quiz_question)) {
                        topic.quiz_question.forEach(question => {
                            if (this.selectedQuestions.includes(question.id)) {
                                question.quiz_question_topic_id = {
                                    id: topic.id,
                                    title: topic.title
                                };
                                filteredQuestions.push(question);
                            }
                        });
                    }
                });
            }

            this.filteredQuestions = {
                data: filteredQuestions,
                total: filteredQuestions.length
            };
        },
        onTopicChange() {
            this.fetchQuestions();
            // Don't clear selected questions when topic changes - preserve selections
        },
        selectAllQuestions() {
            // Select all questions from current filtered results
            if (this.filteredQuestions.data) {
                this.selectedQuestions = this.filteredQuestions.data.map(q => q.id);
            }
        },
        deselectAllQuestions() {
            this.selectedQuestions = [];
        },
        reset_fields: function () {
            this.form_fields.forEach((item) => {
                item.value = "";
            });
        },
        set_fields: async function (id) {
            this.param_id = id;
            await this.details(id);
            if (this.item) {
                this.form_fields.forEach((field, index) => {
                    Object.entries(this.item).forEach((value) => {
                        if (field.name == value[0]) {
                            this.form_fields[index].value = value[1];
                        }
                        // If the field is a textarea, set its summernote content dynamically
                        if (field.type === "textarea" && field.name === value[0]) {
                            $(`#${field.name}`).summernote("code", value[1]);
                        }
                    });
                });
                // Set selected questions for edit
                if (this.item.quiz_questions && Array.isArray(this.item.quiz_questions)) {
                    this.selectedQuestions = this.item.quiz_questions.map(q => q.id);
                }
            }
        },
        submitHandler: async function ($event) {
            this.set_only_latest_data(true);
            this.setSummerEditor();

            // Add hidden inputs for quiz questions to the form
            const form = $event.target;

            // Remove any existing quiz_questions[] inputs
            const existingInputs = form.querySelectorAll('input[name="quiz_questions[]"]');
            existingInputs.forEach(input => input.remove());

            // Add selected quiz questions as hidden inputs
            this.selectedQuestions.forEach(qid => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'quiz_questions[]';
                hiddenInput.value = qid;
                form.appendChild(hiddenInput);
            });

            let response;
            if (this.param_id) {
                response = await this.update($event);
                if ([200, 201].includes(response.status)) {
                    window.s_alert("Data successfully updated");
                    this.$router.push({ name: `Details${this.setup.route_prefix}` });
                }
            } else {
                response = await this.create($event);
                if ([200, 201].includes(response.status)) {
                    $event.target.reset();
                    this.form_fields.forEach(field => {
                        if (field.type === 'textarea' && $(`#${field.name}`).length) {
                            $(`#${field.name}`).summernote("code", '');
                        }
                    });
                    this.selectedQuestions = []; // Clear selected questions
                    window.s_alert("Data Successfully Created");
                }
            }
        },
        setSummerEditor() {
            // Dynamically set summernote content for all textarea fields
            this.form_fields.forEach(field => {
                if (field.type === 'textarea' && $(`#${field.name}`).length) {
                    const markupStr = $(`#${field.name}`).summernote("code");
                    // Set the value in the form field object
                    field.value = markupStr;
                    // Optionally, update a hidden input if your backend expects it
                    let $input = $(`#${field.name}_hidden`);
                    if ($input.length === 0) {
                        $input = $(`<input type="hidden" id="${field.name}_hidden" name="${field.name}">`);
                        $(`#${field.name}`).parent().append($input);
                    }
                    $input.val(markupStr);
                }
            });
        },
    },
    computed: {
        ...mapState(store, {
            item: "item",
        }),
    },
};
</script>
