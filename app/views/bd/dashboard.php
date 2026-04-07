<div class="container">

    <!-- HEADER -->
    <div class="dashboard-header">
        <h2>Business Development Dashboard 🚀</h2>
        <p>Track leads, deals and conversions</p>
    </div>

    <!-- STATS CARDS -->
    <div class="row">

        <div class="card">
            <h4>Total Leads</h4>
            <h2><?= $data['totalLeads']; ?></h2>
        </div>

        <div class="card">
            <h4>Active Deals</h4>
            <h2><?= $data['activeDeals']; ?></h2>
        </div>

        <div class="card">
            <h4>Closed Deals</h4>
            <h2><?= $data['closedDeals']; ?></h2>
        </div>

        <div class="card proposals-card" onclick="goToProposals()">
    <h3>Proposals</h3>
    <p>Review and status updates.</p>
</div>

    </div>

    <!-- RECENT LEADS -->
    <div class="card mt-4">
        <h3>Recent Leads</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>

                <?php if(!empty($data['recentLeads'])): ?>
                    <?php foreach($data['recentLeads'] as $lead): ?>
                        <tr>
                            <td><?= $lead['company_name']; ?></td>
                            <td><?= $lead['contact_person']; ?></td>
                            <td><?= $lead['status']; ?></td>
                            <td><?= $lead['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No leads found</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>
<script>
function goToProposals() {
    window.location.href = "/bd/proposals";
}
</script>