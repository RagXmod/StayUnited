export function createFormData(object, form, namespace) {
    var formData = form || new FormData();
    for (var property in object) {
        if (!object.hasOwnProperty(property) || !object[property]) {
            continue;
        }
        var formKey = namespace ? namespace + "[" + property + "]" : property;
        if (object[property] instanceof Date) {
            formData.append(formKey, object[property].toISOString());
        }
        else if (typeof object[property] === 'object' && !(object[property] instanceof File)) {
            createFormData(object[property], formData, formKey);
        }
        else {
            formData.append(formKey, object[property]);
        }
    }
    return formData;
}

