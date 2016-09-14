<?php

namespace myocuhub\Services;

class ICalService
{
    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var datetime
     */
    private $timezoneICal;

    /**
     *
     * @var string
     * @abstract the starting date (in seconds since unix epoch)
     */
    private $dateStart;

    /**
     *
     * @var string
     * @abstract text title of the event
     */
    private $summary;

    /**
     *
     * @var datetime
     * @abstract the ending date (in seconds since unix epoch)
     */
    private $dateEnd;
    /**
     *
     * @var string
     * @abstract the name of this file for saving (e.g. my-event-name.ics)
     */
    private $filename;

    /**
     *
     * @var string
     * @abstract the event's address
     */
    private $address;

    /**
     *
     * @var string text description of the event
     */
    private $description;

    /**
     *
     * @var bool default false
     */
    private $alarm = false;

    /**
     *
     * @var bool default false
     */
    private $repeat = false;

    private $userName;
    private $userEmail;
    private $providerName;
    private $locationEmail;
    private $patientName;
    private $patientEmail;

    public function __construct($attr)
    {
        if (isset($attr['event_name'])) {
            $this->setName($attr['event_name']);
        }

        if (isset($attr['description'])) {
            $this->setDescription($attr['description']);
        }

        if (isset($attr['date_start'])) {
            $this->setDateStart($attr['date_start']);
        }

        if (isset($attr['date_end'])) {
            $this->setDateEnd($attr['date_end']);
        }

        if (isset($attr['user_name'])) {
            $this->setUserName($attr['user_name']);
        }

        if (isset($attr['user_email'])) {
            $this->setUserEmail($attr['user_email']);
        }

        if (isset($attr['provider_name'])) {
            $this->setProviderName($attr['provider_name']);
        }

        if (isset($attr['provider_email'])) {
            $this->setProviderEmail($attr['provider_email']);
        }

        if (isset($attr['location_email'])) {
            $this->setLocationEmail($attr['location_email']);
        }

        if (isset($attr['patient_name'])) {
            $this->setPatienName($attr['patient_name']);
        }

        if (isset($attr['patient_email'])) {
            $this->setPatienEmail($attr['patient_email']);
        }

        if (isset($attr['address'])) {
            $this->setAddress($attr['address']);
        }

        if (isset($attr['summary'])) {
            $this->setSummary($attr['summary']);
        }

        if (isset($attr['timezone'])) {
            $this->setTimezoneICal($attr['timezone']);
        }

        $this->setFilename(uniqid());
    }




    public function getName()
    {
        return $this->name;
    }

    public function getTimezoneICal()
    {
        return $this->timezoneICal;
    }

    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAlarm()
    {
        return $this->alarm;
    }

    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     *
     * @param type $dateEventStart
     * @return \ical\ical
     */
    public function setDateEventStart($dateEventStart)
    {
        $this->dateEventStart = $dateEventStart;
        return $this;
    }

    /**
     *
     * @param type $dateEventEnd
     * @return \ical\ical
     */
    public function setDateEventEnd($dateEventEnd)
    {
        $this->dateEventEnd = $dateEventEnd;
        return $this;
    }

    /**
     *
     * @param type $name
     * @return \ical\ical
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param type $timezoneICal
     * @return \ical\ical
     */
    public function setTimezoneICal($timezoneICal)
    {
        $this->timezoneICal = $timezoneICal;
        return $this;
    }

    /**
     *
     * @param \DateTime $dateStart
     * @return \ical\ical
     */
    public function setDateStart(\DateTime $dateStart)
    {
        $this->dateStart = $this->dateToCal($this->getHumanToUnix($dateStart->format('Y/m/d H:i:s')));
        return $this;
    }

    /**
     *
     * @param type $summary
     * @return \ical\ical
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     *
     * @param \DateTime $dateEnd
     * @return \ical\ical
     */
    public function setDateEnd(\DateTime $dateEnd)
    {
        $this->dateEnd = $this->dateToCal($this->getHumanToUnix($dateEnd->format('Y/m/d H:i:s')));
        return $this;
    }

    /**
     *
     * @param type $filename
     * @return \ical\ical
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     *
     * @param type $address
     * @return \ical\ical
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     *
     * @param type $description
     * @return \ical\ical
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     *
     * @param type $alarm
     * @return \ical\ical
     * @throws \Exception
     */
    public function setAlarm($alarm)
    {
        if (is_int($alarm)) {
            $this->alarm = $alarm;
            return $this;
        } else {
            throw new \Exception(__CLASS__ . " : It's not an integer", 01);
        }
    }

    /**
     *
     * @param type $repeat
     * @return \ical\ical
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;
        return $this;
    }

    /**
     * @name getICAL()
     * @access public
     * @return string $iCal
     */
    public function getICAL()
    {
        $iCal = "BEGIN:VCALENDAR" . "\r\n";
        $iCal .= "X-WR-TIMEZONE:".$this->getTimezoneICal() . "\r\n";
        $iCal .= 'VERSION:2.0' . "\r\n";
        $iCal .= "PRODID:" . $this->getName() . "\r\n";
        $iCal .= "CALSCALE:GREGORIAN " . "\r\n";
        $iCal .= "BEGIN:VEVENT" . "\r\n";
        $iCal .= "TZNAME:".$this->getTimezoneICal() . "\r\n";
        $iCal .= "DTSTART:" . $this->getDateStart() . "\r\n";
        $iCal .= "DTEND:" . $this->getDateEnd() . "\r\n";
        $iCal .= "DTSTAMP:20160802T153211:". "\r\n";
        $iCal .= "ORGANIZER;CN=". $this->getUserName() .":mailto:". $this->getUserEmail()  . "\r\n";
        $iCal .= "TZID:".$this->getTimezoneICal()  . "\r\n";

        $iCal .= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=". $this->getProviderName().";X-NUM-GUESTS=0:mailto:". $this->getLocationEmail(). "\r\n";

        $iCal .= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=ACCEPTED;RSVP=TRUE;CN=". $this->getPatientName() .";X-NUM-GUESTS=0:mailto:".$this->getPatientEmail() . "\r\n";

        $iCal .= 'STATUS:CONFIRMED' . "\r\n";
        $iCal .= 'TRANSP:OPAQUE' . "\r\n";

        $iCal .= "SUMMARY:" . $this->escapeString($this->getSummary()) . "\r\n";
        $iCal .= 'UID:' . uniqid() . "\r\n";
        $iCal .= 'LOCATION: ' . $this->escapeString($this->getAddress()) . "\r\n";
        $iCal .= 'DESCRIPTION:' . $this->escapeString($this->getDescription()) . "\r\n";

        if ($this->getAlarm()) {
            $iCal .= 'BEGIN:VALARM' . "\r\n";
            $iCal .= 'ACTION:DISPLAY' . "\r\n";
            $iCal .= 'DESCRIPTION:Reminder' . "\r\n";
            $iCal .= 'TRIGGER:-PT' . $this->getAlarm() . 'M' . "\r\n";
            if ($this->getRepeat()) {
                $iCal .= 'REPEAT:' . $this->getRepeat() . "\r\n";
            }
            $iCal .= "END:VALARM" . "\r\n";
        }

        $iCal .= 'END:VEVENT' . "\r\n";
        $iCal .= 'END:VCALENDAR' . "\r\n";
        return $iCal;
    }

    /**
     * @name dateToCal()
     * @access private
     * @param \DateTime $timestamp
     * @return string
     */
    private function dateToCal(\DateTime $timestamp)
    {
        return $timestamp->format('Ymd\THis');
    }

    /**
     * @name escapeString()
     * @abstract Escapes a string of characters
     * @param string $string
     * @return string
     */
    private function escapeString($string)
    {
        return preg_replace('/([\,;])/', '\\\$1', $string);
    }

    /**
     * @name $addHeader();
     * @return headers and file
     */
    public function addHeader()
    {
        header('Content-type: text/calendar; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $this->getFilename() . '.ics');
    }

    /**
     * @name humanToUnix()
     * @param string $datestr like Y-m-d
     * @return bool
     * @return integer
     */
    private function getHumanToUnix($datestr = '')
    {
        if ($datestr === '') {
            return false;
        }
        $datestr = preg_replace('/\040+/', ' ', trim($datestr));
        //dd($datestr);
        /* if (!preg_match('/^(\d{2}|\d{4})\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(?::[0-9]{1,2})?(?:\s[AP]M)?$/i', $datestr)) {
          return false;
          } */
        sscanf($datestr, '%d/%d/%d %s %s', $year, $month, $day, $time, $ampm);

        sscanf($time, '%d:%d:%d', $hour, $min, $sec);
        isset($sec) or $sec = 0;
        if (isset($ampm)) {
            $ampm = strtolower($ampm);
            if ($ampm[0] === 'p' && $hour < 12) {
                $hour += 12;
            } elseif ($ampm[0] === 'a' && $hour === 12) {
                $hour = 0;
            }
        }

        $return = new \DateTime();
        $return->setTimestamp(mktime($hour, $min, $sec, $month, $day, $year));
        return $return;
    }



    public function setUserName($name)
    {
        $this->userName = $name;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserEmail($email)
    {
        $this->userEmail = $email;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function setProviderName($name)
    {
        $this->providerName = $name;
    }

    public function getProviderName()
    {
        return $this->providerName;
    }

    public function setProviderEmail($email)
    {
        $this->providerEmail = $email;
    }

    public function getProviderEmail()
    {
        return $this->providerEmail;
    }

    public function setLocationEmail($email)
    {
        $this->locationEmail = $email;
    }

    public function getLocationEmail()
    {
        return $this->locationEmail;
    }

    public function setPatienName($name)
    {
        $this->patientName = $name;
    }

    public function getPatientName()
    {
        return $this->patientName;
    }

    public function setPatienEmail($email)
    {
        $this->patientEmail = $email;
    }

    public function getPatientEmail()
    {
        return $this->patientEmail;
    }

    /**
     *
     * @var string
     * @learn from https://cestmonvoyage.wordpress.com/2015/12/07/add-to-calender-link-in-mailchimp/
     */
    public function googleCalenderLink()
    {
        $link = 'https://www.google.com/calendar/render?action=TEMPLATE';
        $title = '&text='. str_replace(' ', '+', $this->getName());
        $dates = '&dates='.$this->getDateStart().'/'.$this->getDateEnd();
        $discription = '&details='. str_replace(' ', '+', str_replace("\\n", "%0A", $this->getDescription()));
        $location = '&location='. str_replace(' ', '+', $this->getAddress());
        return $link.$title.$dates.$discription.$location;
    }
}
