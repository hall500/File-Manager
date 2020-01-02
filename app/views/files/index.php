<div id="nav">
    <form action="./files/upload" class="upload" method="post" enctype="multipart/form-data">
        <input type="file" id="file" name="files[]" class="inputfile" data-multiple-caption="{count} files selected"
            multiple>
        <label for="file"><span>Choose a file</span></label>
        <button type="submit" name="submit"><i class="fa fa-upload"></i> Upload</button>
    </form>

    <a href="./files/logout" class="" style="background-color: red;"><i class="fa fa-lock"></i> Logout</a>
    <a href="javascript:void(0);" class="newfolder"><i class="fa fa-folder"></i> New folder</a>
</div>

<div class="filemanager">

    <div class="search">
        <input type="search" placeholder="Find a file.." />
    </div>

    <div class="breadcrumbs"></div>

    <ul class="data"></ul>

    <div class="nothingfound">
        <div class="nofiles"></div>
        <span>No files here.</span>
    </div>

</div>

<?php //$_SESSION['_appid_copy_link'] = './test'; ?>
<?php //unset($_SESSION['_appid_copy_link']); ?>
<div class="menu">
    <ul class="menu-options">
        <li class="menu-option" data-action="view">View</li>
        <li class="menu-option" data-action="download">Download</li>
        <li class="menu-option" data-action="<?= (isset($_SESSION['_appid_copy_link'])) ? 'paste' : 'copy' ?>">
            <?= (isset($_SESSION['_appid_copy_link'])) ? 'Paste' : 'Copy' ?></li>
        <li class="menu-option" data-action="<?= (isset($_SESSION['_appid_move_link'])) ? 'movehere' : 'move' ?>">
            <?= (isset($_SESSION['_appid_move_link'])) ? 'Move Here' : 'Move' ?></li>
        <li class="menu-option" data-action="rename">Rename</li>
        <li class="menu-option" data-action="delete">Delete</li>
        <li class="menu-option" data-action="exit">Exit</li>
    </ul>
</div>

<script type="text/javascript">
const item = document.querySelectorAll(".menu");

const menu = document.querySelector(".menu");
let menuVisible = false;
let link = '';

const toggleMenu = command => {
    menu.style.display = command === "show" ? "block" : "none";
    menuVisible = !menuVisible;
};

const setPosition = ({
    top,
    left
}) => {
    var w = window.innerWidth ||
        document.documentElement.clientWidth ||
        document.body.clientWidth;

    var h = window.innerHeight ||
        document.documentElement.clientHeight ||
        document.body.clientHeight;
    console.log(h);
    console.log(w);
    menu.style.left = `${left}px`;
    menu.style.top = `${top}px`;
    toggleMenu("show");
};

const renameFile = (link) => {
    hash = decodeURIComponent(window.location.hash).slice(1).split('=');

    var file_name = prompt('Rename File', 'NewFolder');

    if (file_name !== null || file_name !== undefined) {
        data = {
            name: file_name,
            hash: hash[0],
            path: link
        };

        const url = './files/re_name';

        fetch(url, {
                method: 'POST',
                body: JSON.stringify(data),
            }).then(response => response.json())
            .then(data => {
                console.log(data);
                //window.location.reload(true);
            })
            .catch(err => console.log("Failed to upload file"));
    }
};

window.addEventListener("click", e => {
    const action = e.target.getAttribute('data-action');
    if (action === null || action === undefined) return false;
    switch (action) {
        case 'view':
            //console.log(link);
            window.location.href = link;
            break;
        case 'download':
            console.log('downloading');
            break;
        case 'copy':
            console.log('copying files');
            break;
        case 'paste':
            console.log('pasting files');
            break;
        case 'move':
            console.log('moving files');
            break;
        case 'movehere':
            console.log('moving files here');
            break;
        case 'rename':
            renameFile();
            console.log('renaming files');
            break;
        case 'delete':
            console.log('deleting files');
            break;
        case 'exit':
            if (menuVisible) toggleMenu("hide");
            return false;
            break;
        default:
            console.log('Nothing selected');
            break;
    }
    if (menuVisible) toggleMenu("hide");
});

window.addEventListener("contextmenu", e => {
    e.preventDefault();
    const item = e.target;
    link = item.getAttribute('data-href');
    if (link === null || link === undefined)
        link = item.getAttribute('href');
    if (link === null || link === undefined) return false;
    //console.log(e.target.getAttribute('href'));

    const origin = {
        left: e.pageX,
        top: e.pageY
    };
    setPosition(origin);
    return false;
});

const url = './files/upload';
const form = document.querySelector('form');
const input = document.querySelector('input.inputfile');

input.addEventListener('change', e => {
    e.preventDefault();

    const label = input.nextElementSibling,
        labelVal = label.innerHTML;

    const files = document.querySelector('[type=file]').files;
    let fileName = '';
    if (files && files.length > 1) {
        fileName = input.getAttribute('data-multiple-caption' || '').replace('{count}', files.length);
    } else {
        fileName = (files[0]) ? files[0].name : 'Choose a file';
    }

    if (fileName)
        label.querySelector('span').innerHTML = fileName;
    else
        label.innerHTML = labelVal;

    form.addEventListener('submit', e => {
        e.preventDefault();
        const formData = new FormData();
        let hash = decodeURIComponent(window.location.hash).slice(1).split('=');

        for (let i = 0; i < files.length; i++) {
            let file = files[i];
            formData.append('files[]', file);
        }

        formData.append('hash', hash[0]);

        fetch(url, {
                method: 'POST',
                body: formData,
            }).then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.reload(true);
            })
            .catch(err => console.log("Failed to upload file"));
    });
});
</script>