(function (context) {
    var $ = context.jQuery;
    var __ = context.wp.i18n.__;

    function CtnBlkStoreMessage(uiContainer, options, props) {
        this.uiContainer = uiContainer;

        if (this.checkCtnApiProxyAvailable(this.uiContainer)) {
            this.showSpinner = props.showSpinner;
            this.spinnerColor = props.spinnerColor;
            this.options = options;
            this.successMsgTemplate = props.successMsgTemplate;
            this.successPanelId = props.successPanelId;
            this.errorPanelId = props.errorPanelId;
            this.form = undefined;
            this.divMessagePanel = undefined;
            this.divDisabledPanel = undefined;
            this.divMsgSuccess = undefined;
            this.divMsgError = undefined;
            this.txtSuccess = undefined;
            this.txtError = undefined;
            this.messageKeyDownHandler = onMessageKeyDown.bind(this);
            this.messageKeyUpHandler = onMessageKeyUp.bind(this);
            this.messageChangeHandler = onMessageChange.bind(this);
            this.lastMessageValue = undefined;

            this.setUpMessagePanel();
            this.setResultPanels();
        }
    }

    CtnBlkStoreMessage.prototype.checkCtnApiProxyAvailable = function (uiContainer) {
        var result = true;

        if (typeof context.ctnApiProxy !== 'object') {
            var elems = $('div.noctnapiproxy', uiContainer.parentElement);
            if (elems.length > 0) {
                var noCtnApiProxy = elems[0];

                noCtnApiProxy.style.display = 'block';
            }

            uiContainer.style.display = 'none';
            result = false;
        }

        return result;
    };

    CtnBlkStoreMessage.prototype.setUpMessagePanel = function () {
        this.uiContainer.style.display = 'block';
        
        var elems = $('form', this.uiContainer);
        if (elems.length > 0) {
            this.form = elems[0];

            this.divMessagePanel = this.form.message.parentElement;

            elems = $('div.disabledPanel', this.divMessagePanel);
            if (elems.length > 0) {
                this.divDisabledPanel = elems[0];
            }
        }
    }

    CtnBlkStoreMessage.prototype.displaySpinner = function () {
        if (!this.spinner) {
            this.spinner = new context.Spin.Spinner({
                className: 'msg-spinner',
                color: this.spinnerColor
            });
        }

        this.spinner.spin(this.divMessagePanel);
    };

    CtnBlkStoreMessage.prototype.hideSpinner = function () {
        if (this.spinner) {
            this.spinner.stop();
        }
    };
    
    CtnBlkStoreMessage.prototype.setResultPanels = function () {
        if (this.successPanelId) {
            // Try to load external success panel control
            var elems = $('#' + this.successPanelId);
            if (elems.length > 0) {
                this.txtSuccess = elems[0];
            }
        }

        if (this.errorPanelId) {
            // Try to load external error panel control
            elems = $('#' + this.errorPanelId);
            if (elems.length > 0) {
                this.txtError = elems[0];
            }
        }

        // Load default panels as necessary
        this.setDefaultResultPanels();
    };

    CtnBlkStoreMessage.prototype.setDefaultResultPanels = function () {
        if (!this.txtSuccess) {
            var elems = $('div.success', this.uiContainer);
            if (elems.length > 0) {
                this.divMsgSuccess = elems[0];

                elems = $('p.success', this.divMsgSuccess);
                if (elems.length > 0) {
                    this.txtSuccess = elems[0];
                }
            }
        }

        if (!this.txtError) {
            elems = $('div.error', this.uiContainer);
            if (elems.length > 0) {
                this.divMsgError = elems[0];

                elems = $('p.error', this.divMsgError);
                if (elems.length > 0) {
                    this.txtError = elems[0];
                }
            }
        }
    };

    CtnBlkStoreMessage.prototype.disableMessagePanel = function () {
        $(this.divMessagePanel).addClass('disabled');
        this.form.message.disabled = true;
        this.divDisabledPanel.style.display = 'block';
    };

    CtnBlkStoreMessage.prototype.enableMessagePanel = function () {
        $(this.divMessagePanel).removeClass('disabled');
        this.form.message.disabled = false;
        this.divDisabledPanel.style.display = 'none';
    };

    CtnBlkStoreMessage.prototype.disableSubmitButton = function () {
        this.form.submitButton.disabled = true;
    }

    CtnBlkStoreMessage.prototype.enableSubmitButton = function () {
        this.form.submitButton.disabled = false;
    }

    CtnBlkStoreMessage.prototype.storeMessage = function () {
        this.clearResultPanels();

        this.disableMessagePanel();
        this.disableSubmitButton();

        if (this.showSpinner) {
            this.displaySpinner();
        }

        var msg = this.form.message.value;

        if (msg.length === 0) {
            // No message to log. Report error
            this.displayError(__('No message to be stored', 'catenis-blocks'));
            return;
        }

        var _self = this;

        context.ctnApiProxy.logMessage(msg, this.options, function(error, result) {
            if (error) {
                _self.displayError(error.toString());
            }
            else {
                if (_self.successMsgTemplate) {
                    _self.displaySuccess(_self.successMsgTemplate.replace(/{!messageId}/g, result.messageId));
                }
                _self.messageStored();
            }
        });
    };

    CtnBlkStoreMessage.prototype.startMonitoringMessageChange = function () {
        this.form.message.addEventListener('keydown', this.messageKeyDownHandler);
        this.form.message.addEventListener('keyup', this.messageKeyUpHandler);
        this.form.message.addEventListener('change', this.messageChangeHandler);
    };

    CtnBlkStoreMessage.prototype.stopMonitoringMessageChange = function () {
        this.form.message.removeEventListener('keydown', this.messageKeyDownHandler);
        this.form.message.removeEventListener('keyup', this.messageKeyUpHandler);
        this.form.message.removeEventListener('change', this.messageChangeHandler);
    };

    CtnBlkStoreMessage.prototype.messageChanged = function () {
        this.enableSubmitButton();
    };

    CtnBlkStoreMessage.prototype.messageStored = function () {
        this.startMonitoringMessageChange();
    };
    
    CtnBlkStoreMessage.prototype.displaySuccess = function (text) {
        this.enableMessagePanel();

        if (this.showSpinner) {
            this.hideSpinner();
        }

        if (this.txtSuccess) {
            $(this.txtSuccess).html(convertLineBreak(text));
            
            if (this.divMsgSuccess) {
                this.divMsgSuccess.style.display = 'block';
            }
        }
    };
    
    CtnBlkStoreMessage.prototype.hideSuccess = function () {
        if (this.txtSuccess) {
            $(this.txtSuccess).html('');

            if (this.divMsgSuccess) {
                this.divMsgSuccess.style.display = 'none';
            }
        }
    };

    CtnBlkStoreMessage.prototype.displayError = function (text) {
        this.enableMessagePanel();
        this.enableSubmitButton();

        if (this.showSpinner) {
            this.hideSpinner();
        }

        if (this.txtError) {
            $(this.txtError).html(convertLineBreak(text));

            if (this.divMsgError) {
                this.divMsgError.style.display = 'block';
            }
        }
    };

    CtnBlkStoreMessage.prototype.hideError = function () {
        if (this.txtError) {
            $(this.txtError).html('');

            if (this.divMsgError) {
                this.divMsgError.style.display = 'none';
            }
        }
    };

    CtnBlkStoreMessage.prototype.clearResultPanels = function () {
        this.hideSuccess();
        this.hideError();
    };

    // eslint-disable-next-line no-unused-vars
    function onMessageKeyDown(event) {
        this.lastMessageValue = this.form.message.value;
    }

    // eslint-disable-next-line no-unused-vars
    function onMessageKeyUp(event) {
        var currentMessageValue = this.form.message.value;

        if (currentMessageValue !== this.lastMessageValue) {
            this.stopMonitoringMessageChange();
            this.messageChanged();
        }
    }

    // eslint-disable-next-line no-unused-vars
    function onMessageChange(event) {
        this.messageStored();
    }

    function convertLineBreak(text) {
        return text.replace(/\n/g, '<br>');
    }

    context.CtnBlkStoreMessage = CtnBlkStoreMessage;
})(this);