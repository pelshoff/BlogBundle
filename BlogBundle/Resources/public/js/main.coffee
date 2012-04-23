setActiveButton = ->
    selectorUrl = location.pathname
    ($ 'li a').parent().removeClass 'active'
    ($ 'li a[href="/"]').parent().addClass 'active'
    while selectorUrl.length > 0
        anchors = ($ 'li a[href="' + selectorUrl + '"]')
        if anchors.length > 0
            ($ 'li a[href="/"]').parent().removeClass 'active'
            anchors.parent().addClass 'active'
            break
        selectorUrl = selectorUrl.substr(0, selectorUrl.lastIndexOf '/')

showModal = (title, content) ->
    modal = ($ '#modal')
    modal.find('h3').html title
    modal.find('.modal-body').html content
    modal.modal()

showAlert = (content, alertClass) ->
    alert = ($ '<div class="alert alert-flash"></div>')
    if alertClass
        alert.addClass 'alert-' + alertClass
    alert.append content
    ($ '.container').append alert
    alert.fadeIn 1000
    setTimeout ->
        alert.fadeOut 1000, ->
            alert.remove()
    , 5000

attachCommentFormEvents = ->
    ($ '#commentForm').on 'submit', (e) ->
        ($ '#commentForm submit').attr 'disabled', true
        e.preventDefault()
        postComment()

postComment = ->
    ($.post location.href, ($ '#commentForm').serialize(), null, 'json')
    .success (e) ->
        ($ '#commentForm').replaceWith e.form
        attachCommentFormEvents()
        if !e || !e.success
            showAlert 'Sorry, but your comment could not be posted at this time', 'error'
            return
        ($ '#commentForm').slideToggle()
        comment = $ e.comment
        ($ '#commentContainer').append comment
        location.href = location.pathname + '#' + comment.attr 'id'
        showAlert 'Thanks for your comment!', 'success'
    .error (e) ->
        ($ '#commentForm submit').attr 'disabled', false
        showAlert 'Sorry, but your comment could not be posted at this time', 'error'

doEveryPageLoad = (e) ->
    setActiveButton()
    attachCommentFormEvents()
    ($ '.commentForm').hide()
    ($ 'code').addClass('highlight').syntaxHighlight()

$(document).ready ->
    $.SyntaxHighlighter.init()

    ajaxNav = new AjaxNavigator ['#content']
    ($ ajaxNav).on 'load', doEveryPageLoad

    ($ '#content').on 'click', 'a.openComments, .commentForm .cancel', (e) ->
        e.preventDefault()
        ($ '.commentForm').slideToggle()

    doEveryPageLoad()