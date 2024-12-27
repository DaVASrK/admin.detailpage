BX.namespace('BX.DVK.Admin');

if (typeof(BX.DVK.Admin.StepperBlock) === 'undefined')
{
    BX.DVK.Admin.StepperBlock = function(id)
    {
        this.id = id;
        this.node = null;
        this.prevButton = null;
        this.nextButton = null;
        this.activeCircleNode = null;
        this.activeTabNode    = null;
        this.currentStep = 1;
        this.totalSteps = 0;
    };

    BX.DVK.Admin.StepperBlock.prototype =
    {
        init: function()
        {
            this._initNodes();
            this._initEvents();
            this._initState();
            this._fixChain();
            this._fixTextarea();
        },

        next: function()
        {
            if (this.currentStep >= this.totalSteps) { return; }

            this.currentStep++;

            this._changeTab();

            this.activeCircleNode = this.activeCircleNode.nextElementSibling;
            this.activeCircleNode.classList.add('active');

            this._checkAccessButtons();
        },
        prev: function()
        {
            if (this.currentStep <= 1) { return; }

            this.currentStep--;

            this._changeTab();

            const lastActiveCircle = this.activeCircleNode;
            lastActiveCircle.classList.add('reverse');
            this.activeCircleNode = this.activeCircleNode.previousElementSibling;
            setTimeout(() => lastActiveCircle.classList.remove('active', 'reverse'), 400);

            this._checkAccessButtons();
        },

        _changeTab: function()
        {
            this.activeTabNode.classList.remove('active');
            this.activeTabNode = this.node.querySelector('div[data-id="tab_' + this.currentStep + '"]');
            this.activeTabNode.classList.add('active');
        },
        _checkAccessButtons: function()
        {
            this.nextButton.style.display = this.currentStep >= this.totalSteps ? 'none' : 'block';
            this.prevButton.style.display = this.currentStep > 1 ? 'block' : 'none';
        },

        _initNodes: function()
        {
            this.node = document.getElementById(this.id);
            this.prevButton = this.node.querySelector('.buttons input.prev');
            this.nextButton = this.node.querySelector('.buttons input.next');
            this.activeCircleNode = this.node.querySelector('.step-chain .step.active');

            this.activeTabNode = this.node.querySelector('div[data-id="tab_1"]');
        },
        _initEvents: function()
        {
            this.prevButton.onclick = this.prev.bind(this);
            this.nextButton.onclick = this.next.bind(this);

            const observer = new IntersectionObserver(
                this._fixChain.bind(this),
                {
                    root: this.node,
                    rootMargin: "0px",
                    threshold: 1.0,
                }
            );
            observer.observe(this.node.querySelector(".step-chain"));
        },
        _initState: function()
        {
            this.totalSteps = this.node.querySelectorAll('.step-tabs .step-tab').length;

            this.prevButton.style.display = 'none';
            if (this.totalSteps <= 1) {
                this.nextButton.style.display = 'none';
            }
        },
        _fixChain: function(entries, observer)
        {
            if (!entries) { return; }
            if (!entries[0].isIntersecting) {
                return;
            }

            const chainLineWidth = this.node.querySelector('.step .step-line').clientWidth;
            const chainWidth = this.node.querySelector('.step-chain').clientWidth;
            const circleWidth = this.node.querySelector('.step-chain .step .step-circle').clientWidth;
            const newChainLineWidth = parseInt(chainWidth / this.totalSteps - circleWidth, 10);

            if (newChainLineWidth < chainLineWidth) {
                this.node.querySelectorAll('.step-chain .step-line').forEach(el => el.style.width = newChainLineWidth + 'px');
            }

            observer.disconnect();
        },
        _fixTextarea: function()
        {
            const textareaList = this.node.querySelectorAll('textarea');
            if (textareaList) {
                for (let textarea of textareaList) {
                    textarea.style.resize    = 'vertical';
                }
            }
        }
    };

    BX.DVK.Admin.StepperBlock.create = function(id)
    {
        const instance = new BX.DVK.Admin.StepperBlock(id);
        instance.init();
        return instance;
    }
}
