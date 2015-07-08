<div class="span4">
    <h3>Contact Form</h3>
    <form id="contact" class="form-horizontal" method="post" />
        <div class="controls">
            <input type="text" id="inputName" placeholder="Name" name="inputName" />
            <label class="ferror" for="inputName" id="fname_error">Name is required.</label>
        </div>
        <div class="controls">
            <input type="text" id="inputEmail" placeholder="Email" name="inputEmail" />
            <label class="ferror" for="inputEmail" id="femail_error">Email is required.</label>
        </div>
        <div class="controls">
            <textarea rows="3" id="inputMessage" placeholder="Message" name="inputMessage"></textarea>
            <label class="ferror" for="inputMessage" id="fmessage_error">Message is required.</label>
        </div>
        <div class="controls">
            <input type="submit" class="btn btn-success btn-large footer-button" value="Submit" />
        </div>
    </form>
</div>