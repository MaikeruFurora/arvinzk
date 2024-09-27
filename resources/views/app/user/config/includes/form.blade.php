<h6>Search Device IP</h6>
<p><b>Reminder:</b> You can search for the device's IP address without entering an actual IP address. However, make sure that you have installed NMAP <a href="https://nmap.org/dist/nmap-7.95-setup.exe">here</a> to search for a ZKTeco device on the network. Once NMAP is installed, try searching to retrieve the device’s IP address and machine information.</p>
<form id="dataForm" class="form-inline" method="GET" action="{{ route('app.user.config.searchIp') }}" autocomplete="off">
    @csrf
    <div class="row g-2 my-1">
        <div class="col-9">
            <input type="text" class="form-control form-control-sm" name="ip" placeholder="IP Address">
        </div>
        <div class="col-3">
            <input type="number" class="form-control form-control-sm" name="port" placeholder="Port" value="4370">
        </div>
    </div>
    <button type="submit" class="btn btn-dark btn-sm my-2 w-100">Search</button>
</form>