<?php
class RankingQuestion extends QuestionModule
{
    protected $answers;
    public function getAnswerHTML()
    {
        global $thissurvey, $showpopups;

        // the future string that goes into the answer segment of templates
        $answer = '';

        $clang=Yii::app()->lang;
        $imageurl = Yii::app()->getConfig("imageurl");

        $checkconditionFunction = "checkconditions";

        $aQuestionAttributes = $this->getAttributeValues();
        $answer = '';
        $ansresult = $this->getAnswers();
        $anscount = count($ansresult);
        if (trim($aQuestionAttributes["max_answers"])!='')
        {
            $max_answers=trim($aQuestionAttributes["max_answers"]);
        } else {
            $max_answers=$anscount;
        }
        $finished=$anscount-$max_answers;
        $answer .= "\t<script type='text/javascript'>\n"
        . "\t<!--\n"
        . "function rankthis_{$this->id}(\$code, \$value)\n"
        . "\t{\n"
        . "\t\$index=document.getElementById('CHOICES_{$this->id}').selectedIndex;\n"
        . "\tfor (i=1; i<=$max_answers; i++)\n"
        . "{\n"
        . "\$b=i;\n"
        . "\$b += '';\n"
        . "\$inputname=\"RANK_{$this->id}\"+\$b;\n"
        . "\$hiddenname=\"fvalue_{$this->id}\"+\$b;\n"
        . "\$cutname=\"cut_{$this->id}\"+i;\n"
        . "document.getElementById(\$cutname).style.display='none';\n"
        . "if (!document.getElementById(\$inputname).value)\n"
        . "\t{\n"
        . "\t\t\t\t\t\t\tdocument.getElementById(\$inputname).value=\$value;\n"
        . "\t\t\t\t\t\t\tdocument.getElementById(\$hiddenname).value=\$code;\n"
        . "\t\t\t\t\t\t\tdocument.getElementById(\$cutname).style.display='';\n"
        . "\t\t\t\t\t\t\tfor (var b=document.getElementById('CHOICES_{$this->id}').options.length-1; b>=0; b--)\n"
        . "\t\t\t\t\t\t\t\t{\n"
        . "\t\t\t\t\t\t\t\tif (document.getElementById('CHOICES_{$this->id}').options[b].value == \$code)\n"
        . "\t\t\t\t\t\t\t\t\t{\n"
        . "\t\t\t\t\t\t\t\t\tdocument.getElementById('CHOICES_{$this->id}').options[b] = null;\n"
        . "\t\t\t\t\t\t\t\t\t}\n"
        . "\t\t\t\t\t\t\t\t}\n"
        . "\t\t\t\t\t\t\ti=$max_answers;\n"
        . "\t\t\t\t\t\t\t}\n"
        . "\t\t\t\t\t\t}\n"
        . "\t\t\t\t\tif (document.getElementById('CHOICES_{$this->id}').options.length == $finished)\n"
        . "\t\t\t\t\t\t{\n"
        . "\t\t\t\t\t\tdocument.getElementById('CHOICES_{$this->id}').disabled=true;\n"
        . "\t\t\t\t\t\t}\n"
        . "\t\t\t\t\tdocument.getElementById('CHOICES_{$this->id}').selectedIndex=-1;\n"
        . "\t\t\t\t\t$checkconditionFunction(\$code);\n"
        . "\t\t\t\t\t}\n"
        . "\t\t\t\tfunction deletethis_{$this->id}(\$text, \$value, \$name, \$thisname)\n"
        . "\t\t\t\t\t{\n"
        . "\t\t\t\t\tvar qid='{$this->id}';\n"
        . "\t\t\t\t\tvar lngth=qid.length+4;\n"
        . "\t\t\t\t\tvar cutindex=\$thisname.substring(lngth, \$thisname.length);\n"
        . "\t\t\t\t\tcutindex=parseFloat(cutindex);\n"
        . "\t\t\t\t\tdocument.getElementById(\$name).value='';\n"
        . "\t\t\t\t\tdocument.getElementById(\$thisname).style.display='none';\n"
        . "\t\t\t\t\tif (cutindex > 1)\n"
        . "\t\t\t\t\t\t{\n"
        . "\t\t\t\t\t\t\$cut1name=\"cut_{$this->id}\"+(cutindex-1);\n"
        . "\t\t\t\t\t\t\$cut2name=\"fvalue_{$this->id}\"+(cutindex);\n"
        . "\t\t\t\t\t\tdocument.getElementById(\$cut1name).style.display='';\n"
        . "\t\t\t\t\t\tdocument.getElementById(\$cut2name).value='';\n"
        . "\t\t\t\t\t\t}\n"
        . "\t\t\t\t\telse\n"
        . "\t\t\t\t\t\t{\n"
        . "\t\t\t\t\t\t\$cut2name=\"fvalue_{$this->id}\"+(cutindex);\n"
        . "\t\t\t\t\t\tdocument.getElementById(\$cut2name).value='';\n"
        . "\t\t\t\t\t\t}\n"
        . "\t\t\t\t\tvar i=document.getElementById('CHOICES_{$this->id}').options.length;\n"
        . "\t\t\t\t\tdocument.getElementById('CHOICES_{$this->id}').options[i] = new Option(\$text, \$value);\n"
        . "\t\t\t\t\tif (document.getElementById('CHOICES_{$this->id}').options.length > 0)\n"
        . "\t\t\t\t\t\t{\n"
        . "\t\t\t\t\t\tdocument.getElementById('CHOICES_{$this->id}').disabled=false;\n"
        . "\t\t\t\t\t\t}\n"
        . "\t\t\t\t\t$checkconditionFunction('');\n"
        . "\t\t\t\t\t}\n"
        . "\t\t\t//-->\n"
        . "\t\t\t</script>\n";
        $ranklist = '';

        foreach ($ansresult as $ansrow)
        {
            $answers[] = array($ansrow['code'], $ansrow['answer']);
        }
        $existing=0;
        for ($i=1; $i<=$anscount; $i++)
        {
            $myfname=$this->fieldname.$i;
            if (isset($_SESSION['survey_'.$this->surveyid][$myfname]) && $_SESSION['survey_'.$this->surveyid][$myfname])
            {
                $existing++;
            }
        }
        for ($i=1; $i<=$max_answers; $i++)
        {
            $myfname = $this->fieldname.$i;
            if (!empty($_SESSION['survey_'.$this->surveyid][$myfname]))
            {
                foreach ($answers as $ans)
                {
                    if ($ans[0] == $_SESSION['survey_'.$this->surveyid][$myfname])
                    {
                        $thiscode = $ans[0];
                        $thistext = $ans[1];
                    }
                }
            }
            $ranklist .= "\t<tr><td class=\"position\">&nbsp;<label for='RANK_{$this->id}$i'>"
            ."$i:&nbsp;</label></td><td class=\"item\"><input class=\"text\" type=\"text\" name=\"RANK_{$this->id}$i\" id=\"RANK_{$this->id}$i\"";
            if (!empty($_SESSION['survey_'.$this->surveyid][$myfname]))
            {
                $ranklist .= " value='";
                $ranklist .= htmlspecialchars($thistext, ENT_QUOTES);
                $ranklist .= "'";
            }
            $ranklist .= " onfocus=\"this.blur()\" />\n";
            $ranklist .= "<input type=\"hidden\" name=\"$myfname\" id=\"fvalue_{$this->id}$i\" value='";
            $chosen[]=""; //create array
            if (!empty($_SESSION['survey_'.$this->surveyid][$myfname]))
            {
                $ranklist .= $thiscode;
                $chosen[]=array($thiscode, $thistext);
            }
            $ranklist .= "' />\n";
            $ranklist .= "<img src=\"$imageurl/cut.gif\" alt=\"".$clang->gT("Remove this item")."\" title=\"".$clang->gT("Remove this item")."\" ";
            if ($i != $existing)
            {
                $ranklist .= "style=\"display:none\"";
            }
            $ranklist .= " id=\"cut_{$this->id}$i\" onclick=\"deletethis_{$this->id}(document.getElementById('RANK_{$this->id}$i').value, document.getElementById('fvalue_{$this->id}$i').value, document.getElementById('RANK_{$this->id}$i').name, this.id)\" /><br />\n";
            $ranklist .= "</td></tr>\n";
        }

        $maxselectlength=0;
        $choicelist = "<select size=\"$anscount\" name=\"CHOICES_{$this->id}\" ";
        if (isset($choicewidth)) {$choicelist.=$choicewidth;}

        $choicelist .= " id=\"CHOICES_{$this->id}\" onchange=\"if (this.options.length>0 && this.selectedIndex<0) { this.options[this.options.length-1].selected=true;}; rankthis_{$this->id}(this.options[this.selectedIndex].value, this.options[this.selectedIndex].text)\" class=\"select\">\n";

        foreach ($answers as $ans)
        {
            if (!in_array($ans, $chosen))
            {
                $choicelist .= "\t\t\t\t\t\t\t<option value='{$ans[0]}'>{$ans[1]}</option>\n";
            }
            if (strlen($ans[1]) > $maxselectlength) {$maxselectlength = strlen($ans[1]);}
        }
        $choicelist .= "</select>\n";

        $answer .= "\t<table border='0' cellspacing='0' class='rank'>\n"
        . "<tr>\n"
        . "\t<td align='left' valign='top' class='rank label'>\n"
        . "<strong>&nbsp;&nbsp;<label for='CHOICES_{$this->id}'>".$clang->gT("Your Choices").":</label></strong><br />\n"
        . "&nbsp;".$choicelist
        . "\t&nbsp;</td>\n";
        $maxselectlength=$maxselectlength+2;
        if ($maxselectlength > 60)
        {
            $maxselectlength=60;
        }
        $ranklist = str_replace("<input class=\"text\"", "<input size='{$maxselectlength}' class='text'", $ranklist);
        $answer .= "\t<td style=\"text-align:left; white-space:nowrap;\" class='rank output'>\n"
        . "\t<table border='0' cellspacing='1' cellpadding='0'>\n"
        . "\t<tr><td></td><td><strong>".$clang->gT("Your Ranking").":</strong></td></tr>\n";

        $answer .= $ranklist
        . "\t</table>\n"
        . "\t</td>\n"
        . "</tr>\n"
        . "<tr>\n"
        . "\t<td colspan='2' class='rank helptext'>\n"
        . "".$clang->gT("Click on the scissors next to each item on the right to remove the last entry in your ranked list")
        . "\t</td>\n"
        . "</tr>\n"
        . "\t</table>\n";

        if (trim($aQuestionAttributes["min_answers"]) != '')
        {
            $minansw=trim($aQuestionAttributes["min_answers"]);
            if(!isset($showpopups) || $showpopups == 0)
            {
                $answer .= "<div id='rankingminanswarning{$this->id}' style='display: none; color: red' class='errormandatory'>"
                .sprintf($clang->ngT("Please rank at least %d item for question \"%s\"","Please rank at least %d items for question \"%s\".",$minansw),$minansw, trim(str_replace(array("\n", "\r"), "", $this->text)))."</div>";
            }
            $minanswscript = "<script type='text/javascript'>\n"
            . "  <!--\n"
            . "  oldonsubmit_{$this->id} = document.limesurvey.onsubmit;\n"
            . "  function ensureminansw_{$this->id}()\n"
            . "  {\n"
            . "     count={$anscount} - document.getElementById('CHOICES_{$this->id}').options.length;\n"
            . "     if (count < {$minansw} && $('#relevance{$this->id}').val()==1){\n";
            if(!isset($showpopups) || $showpopups == 0)
            {
                $minanswscript .= "\n
                document.getElementById('rankingminanswarning{$this->id}').style.display='';\n";
            } else {
                $minanswscript .="
                alert('".sprintf($clang->ngT("Please rank at least %d item for question \"%s\"","Please rank at least %d items for question \"%s\"",$minansw,'js'),$minansw, trim(javascriptEscape(str_replace(array("\n", "\r"), "",$this->text),true,true)))."');\n";
            }
            $minanswscript .= ""
            . "     return false;\n"
            . "   } else {\n"
            . "     if (oldonsubmit_{$this->id}){\n"
            . "         return oldonsubmit_{$this->id}();\n"
            . "     }\n"
            . "     return true;\n"
            . "     }\n"
            . "  }\n"
            . "  document.limesurvey.onsubmit = ensureminansw_{$this->id}\n"
            . "  -->\n"
            . "  </script>\n";
            $answer = $minanswscript . $answer;
        }

        return $answer;
    }

    public function getDataEntry($idrow, &$fnames, $language)
    {
        $clang = Yii::app()->lang;
        $currentvalues=array();
        $myfname=$this->surveyid.'X'.$this->gid.'X'.$this->id;
        $q = $this;
        while ($q->id==$this->id)
        {
            //Let's get all the existing values into an array
            if ($idrow[$q->fieldname])
            {
                $currentvalues[] = $idrow[$q->fieldname];
            }
            if(!$fname=next($fnames)) break;
            $q=$fname['q'];
        }
        $ansquery = "SELECT * FROM {{answers}} WHERE language = '{$language}' AND qid=$thisqid ORDER BY sortorder, answer";
        $ansresult = dbExecuteAssoc($ansquery);
        $anscount = $ansresult->count();
        $output = "\t<script type='text/javascript'>\n"
        ."\t<!--\n"
        ."function rankthis_$thisqid(\$code, \$value)\n"
        ."\t{\n"
        ."\t\$index=document.editresponse.CHOICES_$thisqid.selectedIndex;\n"
        ."\tfor (i=1; i<=$anscount; i++)\n"
        ."{\n"
        ."\$b=i;\n"
        ."\$b += '';\n"
        ."\$inputname=\"RANK_$thisqid\"+\$b;\n"
        ."\$hiddenname=\"d$myfname\"+\$b;\n"
        ."\$cutname=\"cut_$thisqid\"+i;\n"
        ."document.getElementById(\$cutname).style.display='none';\n"
        ."if (!document.getElementById(\$inputname).value)\n"
        ."\t{\n"
        ."\tdocument.getElementById(\$inputname).value=\$value;\n"
        ."\tdocument.getElementById(\$hiddenname).value=\$code;\n"
        ."\tdocument.getElementById(\$cutname).style.display='';\n"
        ."\tfor (var b=document.getElementById('CHOICES_$thisqid').options.length-1; b>=0; b--)\n"
        ."{\n"
        ."if (document.getElementById('CHOICES_$thisqid').options[b].value == \$code)\n"
        ."\t{\n"
        ."\tdocument.getElementById('CHOICES_$thisqid').options[b] = null;\n"
        ."\t}\n"
        ."}\n"
        ."\ti=$anscount;\n"
        ."\t}\n"
        ."}\n"
        ."\tif (document.getElementById('CHOICES_$thisqid').options.length == 0)\n"
        ."{\n"
        ."document.getElementById('CHOICES_$thisqid').disabled=true;\n"
        ."}\n"
        ."\tdocument.editresponse.CHOICES_$thisqid.selectedIndex=-1;\n"
        ."\t}\n"
        ."function deletethis_$thisqid(\$text, \$value, \$name, \$thisname)\n"
        ."\t{\n"
        ."\tvar qid='$thisqid';\n"
        ."\tvar lngth=qid.length+4;\n"
        ."\tvar cutindex=\$thisname.substring(lngth, \$thisname.length);\n"
        ."\tcutindex=parseFloat(cutindex);\n"
        ."\tdocument.getElementById(\$name).value='';\n"
        ."\tdocument.getElementById(\$thisname).style.display='none';\n"
        ."\tif (cutindex > 1)\n"
        ."{\n"
        ."\$cut1name=\"cut_$thisqid\"+(cutindex-1);\n"
        ."\$cut2name=\"d$myfname\"+(cutindex);\n"
        ."document.getElementById(\$cut1name).style.display='';\n"
        ."document.getElementById(\$cut2name).value='';\n"
        ."}\n"
        ."\telse\n"
        ."{\n"
        ."\$cut2name=\"d$myfname\"+(cutindex);\n"
        ."document.getElementById(\$cut2name).value='';\n"
        ."}\n"
        ."\tvar i=document.getElementById('CHOICES_$thisqid').options.length;\n"
        ."\tdocument.getElementById('CHOICES_$thisqid').options[i] = new Option(\$text, \$value);\n"
        ."\tif (document.getElementById('CHOICES_$thisqid').options.length > 0)\n"
        ."{\n"
        ."document.getElementById('CHOICES_$thisqid').disabled=false;\n"
        ."}\n"
        ."\t}\n"
        ."\t//-->\n"
        ."\t</script>\n";
        foreach ($ansresult->readAll() as $ansrow) //Now we're getting the codes and answers
        {
            $answers[] = array($ansrow['code'], $ansrow['answer']);
        }
        //now find out how many existing values there are

        $chosen[]=""; //create array
        if (!isset($ranklist)) {$ranklist="";}

        if (isset($currentvalues))
        {
            $existing = count($currentvalues);
        }
        else {$existing=0;}
        for ($j=1; $j<=$anscount; $j++) //go through each ranking and check for matching answer
        {
            $k=$j-1;
            if (isset($currentvalues) && isset($currentvalues[$k]) && $currentvalues[$k])
            {
                foreach ($answers as $ans)
                {
                    if ($ans[0] == $currentvalues[$k])
                    {
                        $thiscode=$ans[0];
                        $thistext=$ans[1];
                    }
                }
            }
            $ranklist .= "$j:&nbsp;<input class='ranklist' id='RANK_$thisqid$j'";
            if (isset($currentvalues) && isset($currentvalues[$k]) && $currentvalues[$k])
            {
                $ranklist .= " value='".$thistext."'";
            }
            $ranklist .= " onFocus=\"this.blur()\"  />\n"
            . "<input type='hidden' id='d$myfname$j' name='$myfname$j' value='";
            if (isset($currentvalues) && isset($currentvalues[$k]) && $currentvalues[$k])
            {
                $ranklist .= $thiscode;
                $chosen[]=array($thiscode, $thistext);
            }
            $ranklist .= "' />\n"
            . "<img src='".Yii::app()->getConfig('imageurl')."/cut.gif' alt='".$clang->gT("Remove this item")."' title='".$clang->gT("Remove this item")."' ";
            if ($j != $existing)
            {
                $ranklist .= "style='display:none'";
            }
            $ranklist .= " id='cut_$thisqid$j' onclick=\"deletethis_$thisqid(document.editresponse.RANK_$thisqid$j.value, document.editresponse.d$myfname$j.value, document.editresponse.RANK_$thisqid$j.id, this.id)\" /><br />\n\n";
        }

        if (!isset($choicelist)) {$choicelist="";}
        $choicelist .= "<select class='choicelist' size='$anscount' name='CHOICES' id='CHOICES_$thisqid' onclick=\"rankthis_$thisqid(this.options[this.selectedIndex].value, this.options[this.selectedIndex].text)\" >\n";
        foreach ($answers as $ans)
        {
            if (!in_array($ans, $chosen))
            {
                $choicelist .= "\t<option value='{$ans[0]}'>{$ans[1]}</option>\n";
            }
        }
        $choicelist .= "</select>\n";
        $output .= "\t<table>\n"
        ."<tr>\n"
        ."\t<td>\n"
        ."<strong>"
        .$clang->gT("Your Choices").":</strong><br />\n"
        .$choicelist
        ."\t</td>\n"
        ."\t<td align='left'>\n"
        ."<strong>"
        .$clang->gT("Your Ranking").":</strong><br />\n"
        .$ranklist
        ."\t</td>\n"
        ."</tr>\n"
        ."\t</table>\n"
        ."\t<input type='hidden' name='multi' value='$anscount' />\n"
        ."\t<input type='hidden' name='lastfield' value='";
        if (isset($multifields)) {$output .= $multifields;}
        $output .= "' />\n";
        prev($fnames);
        return $output;
    }

    protected function getAnswers()
    {
        if ($this->answers) return $this->answers;
        $aQuestionAttributes = $this->getAttributeValues();
        if ($aQuestionAttributes['random_order']==1) {
            $ansquery = "SELECT * FROM {{answers}} WHERE qid=$this->id AND language='".$_SESSION['survey_'.$this->surveyid]['s_lang']."' and scale_id=0 ORDER BY ".dbRandom();
        } else {
            $ansquery = "SELECT * FROM {{answers}} WHERE qid=$this->id AND language='".$_SESSION['survey_'.$this->surveyid]['s_lang']."' and scale_id=0 ORDER BY sortorder, answer";
        }
        return $this->children = dbExecuteAssoc($ansquery)->readAll();  //Checked
    }
    
    public function getTitle()
    {
        $clang=Yii::app()->lang;
        $aQuestionAttributes = $this->getAttributeValues();
        $max_answers = trim($aQuestionAttributes["max_answers"])!=''?trim($aQuestionAttributes["max_answers"]):count($this->getAnswers());
        if ($max_answers > 1 && $aQuestionAttributes['hide_tip']==0 && trim($aQuestionAttributes['min_answers'])!='')
        {
           return $this->text."<br />\n<span class=\"questionhelp\">".sprintf($clang->ngT("Check at least %d item.","Check at least %d items.",$aQuestionAttributes['min_answers']),$aQuestionAttributes['min_answers'])."</span>";
        }
        return $this->text;
    }
    
    public function getHelp()
    {
        $clang=Yii::app()->lang;
        $aQuestionAttributes = $this->getAttributeValues();
        $help = '';
        $max_answers = trim($aQuestionAttributes["max_answers"])!=''?trim($aQuestionAttributes["max_answers"]):count($this->getAnswers());
        if ($max_answers > 1 && $aQuestionAttributes['hide_tip']==0)
        {
            $help = $clang->gT("Click on an item in the list on the left, starting with your highest ranking item, moving through to your lowest ranking item.");
            if (trim($aQuestionAttributes['min_answers'])!='')
            {
                $help .=' '.sprintf($clang->ngT("Check at least %d item.","Check at least %d items.",$aQuestionAttributes['min_answers']),$aQuestionAttributes['min_answers']);
            }
        }
        return $help;
    }
    
    public function createFieldmap($type=null)
    {
        $clang = Yii::app()->lang;
        $data = Answers::model()->findAllByAttributes(array('qid' => $this->id, 'language' => $this->language));
        for ($i=1; $i<=count($data); $i++)
        {
            $fieldname="{$this->surveyid}X{$this->gid}X{$this->id}$i";
            $field['fieldname']=$fieldname;
            $field['type']=$type;
            $field['sid']=$this->surveyid;
            $field['gid']=$this->gid;
            $field['qid']=$this->id;
            $field['aid']=$i;
            $field['title']=$this->title;
            $field['question']=$this->text;
            $field['subquestion']=sprintf($clang->gT('Rank %s'),$i);
            $field['group_name']=$this->groupname;
            $field['mandatory']=$this->mandatory;
            $field['hasconditions']=$this->conditionsexist;
            $field['usedinconditions']=$this->usedinconditions;
            $field['questionSeq']=$this->questioncount;
            $field['groupSeq']=$this->groupcount;
            $q = clone $this;
            $q->fieldname = $fieldname;
            $q->aid = $field['aid'];
            $q->sq=sprintf($clang->gT('Rank %s'),$i);
            $field['q']=$q;
            $map[$fieldname]=$field;
        }
        return $map;
    }
        
    public function getExtendedAnswer($value, $language)
    {
        if ($value == "-oth-")
        {
            return $language->gT("Other")." [$value]";
        }
        $result = Answers::model()->getAnswerFromCode($this->id,$value,$language->langcode) or die ("Couldn't get answer type."); //Checked
        if($result->count())
        {
            $result =array_values($result->readAll());
            return $result[count($result)-1]." [$value]";
        }
        return $value;
    }
    
    public function getFullAnswer($answerCode, $export, $survey)
    {
        $answers = $survey->getAnswers($this->id);
        if (array_key_exists($answerCode, $answers))
        {
            return $answers[$answerCode]['answer'];
        }
        else
        {
            return null;
        }
    }
    
    public function getFieldSubHeading($survey, $export, $code)
    {
        return ' ['.$export->translate('Ranking', $export->languageCode).' '.$this->aid.']';
    }
    
    public function getSPSSAnswers()
    {
        global $language, $length_vallabel;
        $query = "SELECT {{answers}}.code, {{answers}}.answer,
        {{questions}}.type FROM {{answers}}, {{questions}} WHERE";

        $query .= " {{answers}}.qid = '".$this->id."' and {{questions}}.language='".$language."' and  {{answers}}.language='".$language."'
        and {{questions}}.qid='".$this->id."' ORDER BY sortorder ASC";
        $result= Yii::app()->db->createCommand($query)->query(); //Checked
        foreach ($result->readAll() as $row)
        {
            $answers[] = array('code'=>$row['code'], 'value'=>mb_substr(stripTagsFull($row["answer"]),0,$length_vallabel));
        }
        return $answers;
    }
    
    public function getAnswerArray($em)
    {
        return (isset($em->qans[$this->id]) ? $em->qans[$this->id] : NULL);
    }
    
    public function availableAttributes($attr = false)
    {
        $attrs=array("statistics_showgraph","statistics_graphtype","hide_tip","hidden","max_answers","min_answers","page_break","public_statistics","random_order","parent_order","random_group");
        return $attr?array_key_exists($attr,$attrs):$attrs;
    }

    public function questionProperties($prop = false)
    {
        $clang=Yii::app()->lang;
        $props=array('description' => $clang->gT("Ranking"),'group' => $clang->gT("Mask questions"),'subquestions' => 0,'class' => 'ranking','hasdefaultvalues' => 0,'assessable' => 1,'answerscales' => 1);
        return $prop?$props[$prop]:$props;
    }
}
?>