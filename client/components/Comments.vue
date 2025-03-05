<template>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Comments</h2>
        <section class="space-y-2 bg-white rounded-lg shadow-sm p-4 sm:p-6">
            <!-- Comment 1 -->
            <Comment :tripId="tripId" v-for="comment in Object.values(nestedComments)" :key="comment.id" :comment="comment" @reply="handleReply" />
            <form @submit.prevent="handleAddComment" class="flex space-x-2">
                <input v-model="newComment" type="text" placeholder="Add a comment..." class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Comment</button>
            </form>
            

        </section>
    </div>
</template>
<script setup>
// const comments = ref([
//     { id: 1, text: "This is the first comment", like: 15, dislike: 3, parent: null },
//     { id: 2, text: "Reply to first comment", like: 7, dislike: 1, parent: 1 },
//     { id: 3, text: "Nested reply to comment 2", like: 5, dislike: 0, parent: 2 },
//     { id: 4, text: "Another reply to first comment", like: 10, dislike: 2, parent: 1 },
//     { id: 5, text: "Reply to comment 4", like: 6, dislike: 1, parent: 4 },
//     { id: 6, text: "This is a new root comment", like: 12, dislike: 2, parent: null },
//     { id: 7, text: "Reply to comment 6", like: 8, dislike: 1, parent: 6 },
//     { id: 8, text: "Nested reply to comment 7", like: 4, dislike: 0, parent: 7 },
//     { id: 9, text: "Deeply nested reply to comment 8", like: 2, dislike: 0, parent: 8 },
//     { id: 10, text: "Another root comment", like: 9, dislike: 1, parent: null },
//     { id: 11, text: "Reply to comment 10", like: 5, dislike: 0, parent: 10 },
//     { id: 12, text: "Nested reply to comment 11", like: 3, dislike: 1, parent: 11 },
//     { id: 13, text: "Another deep reply to comment 12", like: 1, dislike: 0, parent: 12 },
//     { id: 14, text: "A new standalone comment", like: 11, dislike: 2, parent: null },
//     { id: 15, text: "Reply to comment 14", like: 7, dislike: 1, parent: 14 },
//     { id: 16, text: "Reply to comment 15", like: 5, dislike: 0, parent: 15 },
//     { id: 17, text: "Another reply to comment 15", like: 3, dislike: 0, parent: 15 },
//     { id: 18, text: "A reply to comment 17", like: 2, dislike: 0, parent: 17 },
//     { id: 19, text: "Reply to comment 18", like: 1, dislike: 0, parent: 18 },
//     { id: 25, text: "Yet another top-level comment", like: 10, dislike: 3, parent: null }
// ]);

const props = defineProps({
    comments: {
        type: Array,
        default: () => []
    },
    tripId:{
        type: Number,
        required: true
    }
})
const newComment = ref('');
const emit  = defineEmits(['update']);
const nestedComments = computed(() => {
    let nested = [];
    props.comments?.map(( comment) => {
        nested[comment.id] = comment;
        
    });
    let keys = Object.keys(nested);
    for (let i = keys.length - 1; i >= 0; i--) {
        const key = keys[i];
        const comment = nested[key];
        if (comment.parent_id) {
            if (!nested[comment.parent_id].replies) {
                nested[comment.parent_id].replies = [];
            }
            nested[comment.parent_id].replies[comment.id] = comment;
        }
    }
    return nested?.filter(predicate => predicate.parent_id === null);

});
console.log(nestedComments.value);
const handleReply = (comment) => {
    emit('update', [comment]);
}
const handleAddComment = async () => {
    try{
        const response = await $fetch('https://trip-planer-api.sankarsan.xyz/api/comments/'+props.tripId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: {
                text: newComment.value,
                parent_id: ''
            }
        });
        newComment.value = '';
        const data = [...props.comments, response];
        console.log(data);
        emit('update',data );
    } catch (error) {
        console.error(error);
    }
}
// console.log(nestedComments.value); // Access the value of the computed property
</script>
