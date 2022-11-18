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

