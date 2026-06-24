<?php
return unserialize('a:3:{s:28:"userTsConfig-package-backend";s:892:"options.enableBookmarks = 1

# Default bookmark groups (can be disabled by setting to 0 or empty)
options.bookmarkGroups {
  1 = core.bookmarks:group_pages
  2 = core.bookmarks:group_records
  3 = core.bookmarks:group_files
  4 = core.bookmarks:group_tools
  5 = core.bookmarks:group_miscellaneous
}

options.pageTree {
  # @deprecated in TYPO3 v14.2 and will be removed in v15.0
  # This value will be ignored if not modified and contains the default value
  doktypesToShowInNewPageDragArea := addToList(1,6,4,7,3,254,199)
  searchInTranslatedPages = 1
  searchByFrontendUri = 1
}

options.contextMenu {
    table {
        pages {
            disableItems =
            tree.disableItems =
        }
        sys_file {
            disableItems =
            tree.disableItems =
        }
        sys_filemounts {
            disableItems =
            tree.disableItems =
        }
    }
}
";s:29:"userTsConfig-package-frontend";s:172:"options {
    saveDocView = 1
    saveDocNew = 1
    saveDocNew.pages = 0
    saveDocNew.sys_file = 0
    saveDocNew.sys_file_metadata = 0
    disableDelete.sys_file = 1
}
";s:29:"userTsConfig-package-filelist";s:157:"options.file_list {
    enableDisplayThumbnails = selectable
    enableClipBoard = selectable
    thumbnail {
        width = 64
        height = 64
    }
}
";}');
#