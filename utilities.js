function setCategoryPOST(category) {
    const path = '/schule-quizule/';
    const params = {'current_category': category};
    post(path, params);
}

function setCurrentQuizPOST(quizId) {
    const path = '/schule-quizule/';
    const params = {'current_quiz': quizId};
    post(path, params);
}

function setCurrentQuizAsOwnerPOST(quizId) {
    const path = '/schule-quizule/';
    const params = {'current_quiz_for_owner': quizId};
    post(path, params);
}

function setSeeCurrentUserDataOptionPOST() {
    const path = '/schule-quizule/';
    const params = {'see_current_user_data': ''};
    post(path, params);
}

function setSeeCurrentUseRoomsOptionPOST() {
    const path = '/schule-quizule/';
    const params = {'see_current_user_rooms': ''};
    post(path, params);
}

function setSeeMyQuizzesOptionPOST() {
    const path = '/schule-quizule/';
    const params = {'see_my_quizzes': ''};
    post(path, params);
}

function setCurrentRoomPOST(roomId) {
    const path = '/schule-quizule/';
    const params = {'see_current_room': roomId};
    post(path, params);
}

function setAddUserToRoomPOST(roomId) {
    const path = '/schule-quizule/';
    const params = {'add_user_to_room': roomId};
    post(path, params);
}

function post(path, params) {

    const form = document.createElement('form');
    form.method = 'post';
    form.action = path;

    for (const key in params) {
        if (params.hasOwnProperty(key)) {
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = key;
            hiddenField.value = params[key];

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function emptyPost() {
    const path = '/schule-quizule/';
    const params = {'main_page': true};
    post(path, params);
}

