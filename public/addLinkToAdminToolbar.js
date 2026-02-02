document.addEventListener(pimcore.events.preMenuBuild, (e) => {
    const menu = e.detail.menu;

    menu.extras.items.push({
        text: t("documentation"),
        iconCls: "pimcore_nav_icon_info",
        itemId: "pimcore_menu_extras_wiki_documentation",
        handler: () => {
            try {
                pimcore.globalmanager.get("torq_wiki_documentation").activate();
            } catch (ex) {
                pimcore.globalmanager.add("torq_wiki_documentation", new Ext.Panel({
                    id: "torq_wiki_documentation_panel",
                    title: t("documentation"),
                    border: false,
                    layout: "fit",
                    closable: true,
                    iconCls: "pimcore_nav_icon_info",
                    items: [
                        {
                            xtype: "component",
                            autoEl: {
                                tag: "iframe",
                                src: "/admin/documentation",
                                style: "width:100%;height:100%;border:none;"
                            }
                        }
                    ]
                }));

                const tabPanel = Ext.getCmp("pimcore_panel_tabs");
                tabPanel.add(pimcore.globalmanager.get("torq_wiki_documentation"));
                tabPanel.setActiveTab(pimcore.globalmanager.get("torq_wiki_documentation"));
            }
        }
    });
});
