BX.namespace('BX.DVK.Admin');

if (typeof(BX.DVK.Admin.ToggleBlock) === 'undefined')
{
    BX.DVK.Admin.ToggleBlock = function(id)
    {
        this.id = id;
        this.node        = null;
        this.topNode     = null;
        this.contentNode = null;
    };

    BX.DVK.Admin.ToggleBlock.prototype =
    {
        init: function()
        {
            this._initNodes();
            this._initEvents();
            this._fixElements();
        },
        toggle: function()
        {
            this.topNode.classList.toggle('adm-detail-title-setting-active');
            this.contentNode.style.display = this.contentNode.style.display === 'none' ? 'block' : 'none';
        },

        _initNodes: function()
        {
            this.node = document.getElementById(this.id);
            this.topNode = this.node.querySelector('.toggle-top');
            this.contentNode = this.node.querySelector('.toggle-content');
        },
        _initEvents: function()
        {
            const button = this.node.querySelector('.toggle-btn');
            button.onclick = this.toggle.bind(this);
        },
        _fixElements: function ()
        {
            const textareaList = this.node.querySelectorAll('textarea');
            if (textareaList) {
                for (let textarea of textareaList) {
                    textarea.style.boxSizing = 'border-box';
                    textarea.style.resize    = 'vertical';
                }
            }
        }
    };

    BX.DVK.Admin.ToggleBlock.create = function(id)
    {
        const instance = new BX.DVK.Admin.ToggleBlock(id);
        instance.init();
        return instance;
    }
}
