<div class="list-group rounded-0">
  <a href="{{ url('dashboard/settings/profile') }}" class="list-group-item rounded-0 list-group-item-action<?php if (\Request::route()->getName() == 'settingsProfile') echo ' active'; ?>"><i class="mi fingerprint"></i> {{ trans('nearby-platform.profile') }}</a>
  <a href="{{ url('dashboard/settings/analytics') }}" class="list-group-item rounded-0 list-group-item-action<?php if (\Request::route()->getName() == 'settingsAnalytics') echo ' active'; ?>"><i class="mi insert_chart"></i> {{ trans('nearby-platform.analytics') }}</a>
</div>