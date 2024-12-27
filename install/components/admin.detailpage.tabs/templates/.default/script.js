BX.namespace('BX.DVK.Admin');

if (typeof(BX.DVK.Admin.TabsBlock) === 'undefined')
{
    BX.DVK.Admin.TabsBlock = function(id)
    {
        this.id = id;
        this.node = null;
        this.currentTabHeader  = null;
        this.currentTabContent = null;
    };

    BX.DVK.Admin.TabsBlock.prototype =
        {
            init: function()
            {
                this._initNodes();
                this._initEvents();
                this._initState();
            },
            changeTab: function(tabId)
            {
                this.currentTabHeader.classList.remove('adm-detail-tab-active');
                this.currentTabContent.classList.remove('active');

                this.currentTabHeader  = this.node.querySelector('.tabs-header [data-id="' + tabId + '"]');
                this.currentTabContent = this.node.querySelector('.tabs-content [data-id="' + tabId + '"]');

                this.currentTabHeader.classList.add('adm-detail-tab-active');
                this.currentTabContent.classList.add('active');
            },

            _initNodes: function()
            {
                this.node = document.getElementById(this.id);
                this.currentTabHeader  = this.node.querySelector('.tabs-header [data-id="tab_1"]');
                this.currentTabContent = this.node.querySelector('.tabs-content [data-id="tab_1"]');
            },
            _initEvents: function()
            {
                this.node.querySelector('.tabs-header').addEventListener('click', this._changeTabEvent.bind(this));
            },
            _initState: function()
            {
                this.currentTabHeader.classList.add('adm-detail-tab-active');
                this.currentTabContent.classList.add('active');
            },
            _changeTabEvent: function(ev)
            {
                if (!ev.target.classList.contains('adm-detail-tab')) { return; }

                this.changeTab(ev.target.dataset.id);
            }
        };

    BX.DVK.Admin.TabsBlock.create = function(id)
    {
        const instance = new BX.DVK.Admin.TabsBlock(id);
        instance.init();
        return instance;
    }
}
