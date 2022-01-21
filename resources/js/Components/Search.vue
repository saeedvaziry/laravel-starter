<template>
    <div class="inline-flex">
        <div v-if="open" @click="close" class="fixed w-full h-full bg-black opacity-40 left-0 right-0 top-0 z-10"></div>
        <div v-if="open" class="absolute w-96 z-20" style="left: 50%; top:10%; transform: translate(-50%, -50%)">
            <v-input id="search-input" ref="input" @focus="focused = true" @blur="focused = false" type="text" class="w-full" @input="isTyping = true" v-model="searchQuery" placeholder="Search something..." @keyup="keyEntered" autocomplete="off"></v-input>
            <v-dropdown ref="dropdown" width="full">
                <template #content>
                    <span v-if="isLoading" class="text-gray-800 px-3 text-sm">Please wait</span>
                    <span v-else-if="noResults" class="text-gray-800 px-3 text-sm">No results</span>
                    <v-dropdown-link v-else :href="item.url" v-for="(item, index) in searchResult" :key="'result-' + index" @click="close" :ref="`result${index}`">
                        <div class="flex items-center justify-between">
                            <span class="text-primary-500 font-semibold">{{ item.text }}</span>
                            <div class="flex items-center">
                                <span class="bg-gray-100 text-gray-500 rounded-xl px-2 mr-1">{{ item.team }}</span>
                                <span class="bg-primary-100 text-primary-500 rounded-xl px-2">{{ item.type }}</span>
                            </div>
                        </div>
                    </v-dropdown-link>
                </template>
            </v-dropdown>
        </div>
        <div @click="show" class="px-2 rounded-md border border-gray-00 text-gray-500 cursor-pointer">
            Press / to search
        </div>
    </div>
</template>

<script>
    import VInput from "@/Components/Input";
    import VDropdown from "@/Components/Dropdown";
    import VDropdownLink from "@/Components/DropdownLink";

    export default {
        name: 'VSearch',
        components: {VDropdownLink, VDropdown, VInput},
        props: ['classes'],
        data() {
            return {
                open: false,
                searchQuery: "",
                isTyping: false,
                searchResult: [],
                isLoading: false,
                keyUp: false,
                noResults: false,
                showResult: false,
                focused: false,
                focusedItem: null
            }
        },
        watch: {
            searchQuery: _.debounce(function () {
                this.isTyping = false;
            }, 700),
            isTyping: function (value) {
                if (!value && this.keyUp && this.searchQuery.length > 0) {
                    this.search(this.searchQuery)
                }
            }
        },
        mounted() {
            document.onkeydown = (e) => {
                if (e.key === '/') {
                    if (!document.activeElement.type || document.activeElement.type === 'undefined') {
                        this.show();
                    }
                }
                if (e.key !== 'Escape') {
                    this.changeFocus(e.key);
                }
            }
        },
        unmounted() {
            document.onkeydown = null;
        },
        methods: {
            show() {
                this.open = true;
                setTimeout(() => {
                    this.$refs.input.focus();
                }, 100);
                document.body.classList.add('overflow-y-hidden');
            },
            close() {
                this.isLoading = false;
                this.searchResult = [];
                this.searchQuery = '';
                this.noResults = false;
                this.focused = false;
                this.open = false;
                document.body.classList.remove('overflow-y-hidden');
            },
            search(searchQuery) {
                this.$refs.dropdown.show();
                this.noResults = false;
                this.isLoading = true;
                window.axios.get('/search?q=' + searchQuery).then((response) => {
                    this.searchResult = response.data.results;
                    if (this.searchResult.length === 0) {
                        this.noResults = true;
                    }
                    this.keyUp = false;
                }).catch((response) => {
                    console.log(response);
                }).then(() => {
                    this.isLoading = false;
                });
            },
            keyEntered(e) {
                this.keyUp = true;
                if (e.code === 'Escape') {
                    this.close();
                }
                this.changeFocus(e.code);
            },
            changeFocus(type) {
                if (type === 'ArrowDown') {
                    if (this.searchResult.length > 0) {
                        if (this.focusedItem !== null) {
                            if (this.focusedItem < this.searchResult.length - 1) {
                                this.focusedItem++;
                            }
                        } else {
                            this.focusedItem = 0;
                        }
                        this.$refs[`result${this.focusedItem}`].focus();
                    }
                }
                if (type === 'ArrowUp') {
                    if (this.searchResult.length > 0) {
                        if (this.focusedItem !== null) {
                            if (this.focusedItem > 0) {
                                this.focusedItem--;
                            }
                            this.$refs[`result${this.focusedItem}`].focus();
                        }
                    }
                }
            }
        }
    }
</script>
